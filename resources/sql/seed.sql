create schema if not exists lbaw2375;
CREATE SCHEMA skybuy;
SET search_path TO lbaw2375;


CREATE FUNCTION update_product_rating() RETURNS TRIGGER AS
$BODY$
BEGIN
    Update product
    Set rating = (SELECT avg(review.rating) from review where id_product = NEW.id_product)
    where product.id_product = NEW.id_product;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_product_rating 
AFTER INSERT OR UPDATE 
ON review
FOR EACH ROW
EXECUTE PROCEDURE update_product_rating(); 

CREATE FUNCTION verify_stock() RETURNS TRIGGER AS
$BODY$
BEGIN
IF NOT EXISTS (
    SELECT * from product
    WHERE NEW.id_product = product.id_product
        AND NEW.quantity <= product.stock
)


THEN RAISE EXCEPTION 'You cannot add that much quantity to the cart';
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_stock 
	BEFORE INSERT ON cart_product
	FOR EACH ROW
	EXECUTE PROCEDURE verify_stock();

CREATE FUNCTION order_status_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
IF EXISTS (
    SELECT * from customer 
    where New.id_customer = customer.id
)
THEN INSERT INTO notification(content, id_customer) VALUES ('Your order status has been updated' , New.id_customer);
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER order_status_notification
AFTER UPDATE ON purchase
FOR EACH ROW
EXECUTE PROCEDURE order_status_notification();

CREATE FUNCTION verify_purchase_for_review() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
    SELECT 1
    FROM purchase p
    WHERE p.id_customer = NEW.id_customer
      AND p.id_cart IN (
        SELECT cp.id_cart
        FROM cart_product cp
        WHERE cp.id_product = NEW.id_product
      )
  ) THEN
    RETURN NEW; -- User has purchased the product, allow the review.
  ELSE
    RAISE EXCEPTION 'You cannot leave a review without purchasing the product.';
  END IF;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_purchase_for_review 
BEFORE INSERT ON review
FOR EACH ROW
EXECUTE PROCEDURE verify_purchase_for_review();

CREATE FUNCTION check_unique_review() RETURNS TRIGGER AS
$BODY$
BEGIN
IF EXISTS (
    SELECT 1 FROM review WHERE id_customer = NEW.id_customer AND id_product = NEW.id_product
) THEN
    RAISE EXCEPTION 'A user can only write one review per product';
END IF;
RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_unique_review 
BEFORE INSERT ON review
FOR EACH ROW
EXECUTE PROCEDURE check_unique_review();

CREATE FUNCTION quantity_higher_twenty() RETURNS TRIGGER AS
$BODY$
BEGIN
IF NOT EXISTS(
    SELECT * from product
    WHERE NEW.id_product = product.id_product 
	AND NEW.quantity <= 20
)

THEN RAISE EXCEPTION 'You cannot add more than 20 identical items to the cart';
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER quantity_higher_twenty
	BEFORE INSERT ON cart_product
	FOR EACH ROW
	EXECUTE PROCEDURE quantity_higher_twenty(); 

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

-- Insert a new review
INSERT INTO review (id_customer, product_id, rating, comment)
VALUES ($id_customer, $product_id, $rating, $comment);

-- Update the product rating
UPDATE product
SET rating = (SELECT AVG(rating) FROM review WHERE product_id = $product_id)
WHERE id_product = $product_id;

END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

SELECT stock FROM product WHERE id_product = $id_product;

UPDATE product SET stock = stock - $purchase_quantity WHERE id = $id_product;

INSERT INTO purchase_order(id_customer, id_product, quantity) VALUES ($id_customer, $id_product, $purchase_quantity);

END TRANSACTION;

-----------------------------------------
--
-- Use this code to drop and create a schema.
-- In this case, the DROP TABLE statements can be removed.
--
DROP SCHEMA skybuy CASCADE;
CREATE SCHEMA skybuy;
SET search_path TO skybuy;

-----------------------------------------
-- Drop old schema
-----------------------------------------

DROP TABLE IF EXISTS account CASCADE;
DROP TABLE IF EXISTS customer CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS product CASCADE;
DROP TABLE IF EXISTS discount CASCADE;
DROP TABLE IF EXISTS review CASCADE;
DROP TABLE IF EXISTS cart CASCADE;
DROP TABLE IF EXISTS cart_product CASCADE;
DROP TABLE IF EXISTS address CASCADE;
DROP TABLE IF EXISTS payment_method CASCADE;
DROP TABLE IF EXISTS purchase CASCADE;
DROP TABLE IF EXISTS paypal CASCADE;
DROP TABLE IF EXISTS card CASCADE;
DROP TABLE IF EXISTS transfer CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS wishlist CASCADE;
DROP TABLE IF EXISTS faq CASCADE;

DROP TYPE IF EXISTS OrderStauts;

-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE OrderStatus as ENUM ('Processing', 'Packed', 'Shipped', 'Delivered');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE account (
    id_account SERIAL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL check (LENGTH(password) > 8),
    email TEXT NOT NULL UNIQUE,
    phone INTEGER,
    is_banned BOOLEAN DEFAULT false,
    profile_pic TEXT DEFAULT 'images/default.jpg',
	
    CONSTRAINT account_PK PRIMARY KEY(id_account)
);

CREATE TABLE cart (
    id_cart SERIAL,

    CONSTRAINT cart_PK PRIMARY KEY(id_cart)
);

CREATE TABLE customer (
    id_customer INTEGER,
    id_cart INTEGER NOT NULL UNIQUE,
   
    CONSTRAINT customer_PK PRIMARY KEY(id_customer),
    CONSTRAINT customer_FK1 FOREIGN KEY(id_customer) REFERENCES account(id_account) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT customer_FK2 FOREIGN KEY(id_cart) REFERENCES cart(id_cart) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE admin (
    id_admin INTEGER,
    
    CONSTRAINT admin_PK PRIMARY KEY(id_admin),
    CONSTRAINT admin_FK FOREIGN KEY(id_admin) REFERENCES account(id_account) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE product (
    id_product SERIAL,
    name TEXT NOT NULL UNIQUE,
    price FLOAT NOT NULL CHECK(price > 0.0),
    size TEXT NOT NULL,
    stock INTEGER NOT NULL CHECK(stock >= 0),
    brand TEXT NOT NULL,
    rating FLOAT NOT NULL DEFAULT 0.0 CHECK(rating >= 0.0 AND rating <= 5.0),
    description TEXT NOT NULL,
    
    CONSTRAINT product_PK PRIMARY KEY(id_product)
);

CREATE TABLE discount (
       id_discount SERIAL,
       amount FLOAT NOT NULL CONSTRAINT amount_ck CHECK (((amount > 0.0) AND (amount <= 1.0))),
       start_date TIMESTAMP WITH TIME ZONE NOT NULL,
       end_date TIMESTAMP WITH TIME ZONE NOT NULL,

       CONSTRAINT date_ck CHECK (end_date > start_date),
       CONSTRAINT discount_PK PRIMARY KEY(id_discount)
);

CREATE TABLE review (
    id_review SERIAL,
    review_text TEXT NOT NULL CHECK (LENGTH(review_text) < 300),
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    id_customer INTEGER NOT NULL,
    id_product INTEGER NOT NULL,

    CONSTRAINT review_PK PRIMARY KEY(id_review),
    CONSTRAINT review_FK1 FOREIGN KEY(id_customer) REFERENCES customer(id_customer) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT review_FK2 FOREIGN KEY(id_product) REFERENCES product(id_product) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE cart_product (
    id_cart INTEGER,
    id_product INTEGER,
    quantity INTEGER NOT NULL,

    CONSTRAINT cart_product_PK PRIMARY KEY (id_cart, id_product),
    CONSTRAINT cart_product_FK1 FOREIGN KEY(id_cart) REFERENCES cart(id_cart) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT cart_product_FK2 FOREIGN KEY(id_product) REFERENCES product(id_product) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE address (
    id_address SERIAL,
    street_name TEXT NOT NULL,
    street_number INTEGER NOT NULL,
    zipcode TEXT NOT NULL,
    country TEXT NOT NULL,

    CONSTRAINT address_PK PRIMARY KEY(id_address)
);

CREATE TABLE payment_method (
    id_payment_method SERIAL NOT NULL,
    CONSTRAINT payment_method_PK PRIMARY KEY(id_payment_method)
);

CREATE TABLE purchase (
    id_purchase SERIAL,
    order_date DATE NOT NULL,
    delivery_date DATE NOT NULL,
    order_status OrderStatus NOT NULL,
    id_customer INTEGER NOT NULL,
    id_address INTEGER NOT NULL,
    id_payment_method INTEGER NOT NULL,
    id_cart INTEGER NOT NULL UNIQUE,

    CHECK (delivery_date > order_date),

    CONSTRAINT order_PK PRIMARY KEY(id_purchase),
    CONSTRAINT order_FK1 FOREIGN KEY(id_customer) REFERENCES customer(id_customer) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT order_FK2 FOREIGN KEY(id_address) REFERENCES address(id_address) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT order_FK3 FOREIGN KEY(id_payment_method) REFERENCES payment_method(id_payment_method) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT order_FK4 FOREIGN KEY(id_cart) REFERENCES cart(id_cart) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE paypal (
    id_paypal INTEGER,
    email TEXT NOT NULL UNIQUE,

    CONSTRAINT paypal_PK PRIMARY KEY(id_paypal),
    CONSTRAINT paypal_FK FOREIGN KEY(id_paypal) REFERENCES payment_method(id_payment_method) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE card (
    id_card INTEGER,
    number BIGINT NOT NULL UNIQUE,
    exp_date DATE NOT NULL CHECK (exp_date > CURRENT_DATE),
    cvv INTEGER NOT NULL CHECK (cvv >= 100 AND cvv <= 999),

    CONSTRAINT card_PK PRIMARY KEY(id_card),
    CONSTRAINT card_FK FOREIGN KEY(id_card) REFERENCES payment_method(id_payment_method) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE transfer (
    id_transfer INTEGER,
    entity INTEGER NOT NULL,
    reference BIGINT NOT NULL UNIQUE,
    valid INTEGER NOT NULL DEFAULT 24,

    CONSTRAINT transfer_PK PRIMARY KEY(id_transfer),
    CONSTRAINT transfer_FK FOREIGN KEY(id_transfer) REFERENCES payment_method(id_payment_method) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE notification (
    id_notification SERIAL,
    content TEXT NOT NULL,
    seen BOOLEAN NOT NULL DEFAULT false,
    id_customer INTEGER NOT NULL,

    CONSTRAINT notification_PK PRIMARY KEY(id_notification),
    CONSTRAINT notification_FK FOREIGN KEY(id_customer) REFERENCES customer(id_customer) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE wishlist (
    id_customer INTEGER,
    id_product INTEGER,
    
    CONSTRAINT wishlist_PK PRIMARY KEY (id_customer, id_product),
    CONSTRAINT wishlist_FK1 FOREIGN KEY(id_customer) REFERENCES customer(id_customer) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT wishlist_FK2 FOREIGN KEY(id_product) REFERENCES product(id_product) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE faq (
    id_faq serial,
    question TEXT NOT NULL UNIQUE,
    answer TEXT NOT NULL,
	
    CONSTRAINT faq_PK PRIMARY KEY(id_faq)
);

-----------------------------------------
-- INDEXES
-----------------------------------------

CREATE INDEX order_user ON purchase(id_customer);
CLUSTER purchase USING order_user;

CREATE INDEX product_price ON product(price);

CREATE INDEX product_brand ON product USING hash(brand);

CREATE INDEX review_author ON review USING hash(id_customer);

CREATE INDEX review_product ON review(id_product);
CLUSTER purchase USING order_user;

CREATE INDEX review_rating ON review(rating);

-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------

-- TRIGGER TO UPDATE THE RATING OF A PRODUCT WHEN A REVIEW IS ADDED OR CHANGED --
CREATE FUNCTION update_product_rating() RETURNS TRIGGER AS
$BODY$
BEGIN
    Update product
    Set rating = (SELECT avg(review.rating) from review where id_product = NEW.id_product)
    where product.id_product = NEW.id_product;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_product_rating 
AFTER INSERT OR UPDATE 
ON review
FOR EACH ROW
EXECUTE PROCEDURE update_product_rating(); 


-- TRIGGER TO PREVENT A USER FROM ADDING MORE ITEMS TO THE CART THAN THE AVAILABLE STOCK
CREATE FUNCTION verify_stock() RETURNS TRIGGER AS
$BODY$
BEGIN
IF NOT EXISTS(
    SELECT * from product
    WHERE NEW.id_product = product.id
        AND NEW.quantity <= product.stock
)

THEN RAISE EXCEPTION 'You cannot add that much quantity to the cart';
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_stock 
	BEFORE INSERT ON cart_product
	FOR EACH ROW
	EXECUTE PROCEDURE verify_stock(); 

-- TRIGGER TO SEND A NOTIFICATION TO THE CUSTOMER WHEN THERE IS A CHANGE OF STATUS IN THEIR ORDER
CREATE FUNCTION order_status_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
IF EXISTS (
    SELECT * from customer 
    where New.id_customer = customer.id
)
THEN INSERT INTO notification(content, id_customer) VALUES ('Your order status has been updated' , New.id_customer);
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER order_status_notification
AFTER UPDATE ON purchase
FOR EACH ROW
EXECUTE PROCEDURE order_status_notification();

-- TRIGGER TO PREVENT A USER FROM LEAVING A REVIEW IF THEY HAVEN'T PURCHASED THE PRODUCT 
--CREATE FUNCTION verify_purchase_for_review() RETURNS TRIGGER AS
--$BODY$
--BEGIN
--    IF EXISTS (
--    SELECT 1
--    FROM purchase p
--    WHERE p.id_customer = NEW.id_customer
--      AND p.id_cart IN (
--        SELECT cp.id_cart
--        FROM cart_product cp
--        WHERE cp.id_product = NEW.id_product
--      )
--  ) THEN
--    RETURN NEW; -- User has purchased the product, allow the review.
--  ELSE
--    RAISE EXCEPTION 'You cannot leave a review without purchasing the product.';
--  END IF;
--END;
--$BODY$
--LANGUAGE plpgsql;

--CREATE TRIGGER verify_purchase_for_review 
--BEFORE INSERT ON review
--FOR EACH ROW
--EXECUTE PROCEDURE verify_purchase_for_review();

--TRIGGER TO ENSURACE THAT A USER CAN ONLY WRITE ONE REVIEW FOR EACH PRODUCT
CREATE FUNCTION check_unique_review() RETURNS TRIGGER AS
$BODY$
BEGIN
IF EXISTS (
    SELECT 1 FROM review WHERE id_customer = NEW.id_customer AND id_product = NEW.id_product
) THEN
    RAISE EXCEPTION 'A user can only write one review per product';
END IF;
RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_unique_review 
BEFORE INSERT ON review
FOR EACH ROW
EXECUTE PROCEDURE check_unique_review();

-- TRIGGER TO PREVENT A USER FROM ADDING MORE THAN 20 IDENTICAL ITEMS TO THE CART 
CREATE FUNCTION quantity_higher_twenty() RETURNS TRIGGER AS
$BODY$
BEGIN
IF NOT EXISTS(
    SELECT * from product
    WHERE NEW.id_product = product.id_product
	AND NEW.quantity <= 20
)

THEN RAISE EXCEPTION 'You cannot add more than 20 identical items to the cart';
END IF;
RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER quantity_higher_twenty 
	BEFORE INSERT ON cart_product
	FOR EACH ROW
	EXECUTE PROCEDURE quantity_higher_twenty();

------------------------------------------------------- account ------------------------------------------------------
INSERT INTO account (id_account, username, password, email) 
VALUES (1, 'up201905383', 'P@ssw0rd123', 'up201905383@up.pt');
INSERT INTO account (id_account, username, password, email) 
VALUES (2, 'up202108723', 'Secur1ty$tr0ng', 'up202108723@up.pt');
INSERT INTO account (id_account, username, password, email) 
VALUES (3, 'up202006525', '9LongerP@ss', 'up202006525@up.pt');
INSERT INTO account (id_account, username, password, email) 
VALUES (4, 'up202108857', '$afeP@ssw0rd7', 'up202108857@up.pt');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (5, 'JohnDoe', 'password123', 'john.doe@example.com', 123456789, false, 'images/john_doe.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (6, 'JaneSmith', 'password456', 'jane.smith@example.com', 987654321, false, 'images/jane_smith.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (7, 'MikeJohnson', 'securepass', 'mike.johnson@example.com', 555555555, false, 'images/mike_johnson.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (8, 'EmilyDavis', 'pass12345', 'emily.davis@example.com', 999999999, false, 'images/emily_davis.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (9, 'SarahBrown', 'passSarah', 'sarah.brown@example.com', 777777777, false, 'images/sarah_brown.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (10, 'DavidWilson', 'david1234', 'david.wilson@example.com', 888888888, false, 'images/david_wilson.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (11, 'OliviaLee', 'olivia456', 'olivia.lee@example.com', 111111111, false, 'images/olivia_lee.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (12, 'JamesSmith', 'jamespass', 'james.smith@example.com', 222222222, false, 'images/james_smith.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (13, 'AvaWilson', 'ava456789', 'ava.wilson@example.com', 333333333, false, 'images/ava_wilson.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (14, 'WilliamJohnson', 'william123', 'william.johnson@example.com', 444444444, false, 'images/william_johnson.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (15, 'SophiaTaylor', 'sophia1234', 'sophia.taylor@example.com', 555555555, false, 'images/sophia_taylor.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (16, 'LiamDavis', 'liam56789', 'liam.davis@example.com', 666666666, false, 'images/liam_davis.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (17, 'IsabellaBrown', 'isabella123', 'isabella.brown@example.com', 777777777, false, 'images/isabella_brown.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (18, 'BenjaminLee', 'benjaminpass', 'benjamin.lee@example.com', 888888888, false, 'images/benjamin_lee.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (19, 'MiaWilson', 'miapass123', 'mia.wilson@example.com', 999999999, false, 'images/mia_wilson.jpg');
INSERT INTO account (id_account, username, password, email, phone, is_banned, profile_pic)
VALUES (20, 'NoahSmith', 'noah12345', 'noah.smith@example.com', 111111111, false, 'images/noah_smith.jpg');

-------------- cart ---------------
INSERT INTO cart (id_cart) VALUES (5);
INSERT INTO cart (id_cart) VALUES (6);
INSERT INTO cart (id_cart) VALUES (7);
INSERT INTO cart (id_cart) VALUES (8);
INSERT INTO cart (id_cart) VALUES (9);
INSERT INTO cart (id_cart) VALUES (10);
INSERT INTO cart (id_cart) VALUES (11);
INSERT INTO cart (id_cart) VALUES (12);
INSERT INTO cart (id_cart) VALUES (13);
INSERT INTO cart (id_cart) VALUES (14);
INSERT INTO cart (id_cart) VALUES (15);
INSERT INTO cart (id_cart) VALUES (16);
INSERT INTO cart (id_cart) VALUES (17);
INSERT INTO cart (id_cart) VALUES (18);
INSERT INTO cart (id_cart) VALUES (19);
INSERT INTO cart (id_cart) VALUES (20);

------------------ customer --------------------
INSERT INTO customer VALUES (5, 5);
INSERT INTO customer VALUES (6, 6);
INSERT INTO customer VALUES (7, 7);
INSERT INTO customer VALUES (8, 8);
INSERT INTO customer VALUES (9, 9);
INSERT INTO customer VALUES (10, 10);
INSERT INTO customer VALUES (11, 11);
INSERT INTO customer VALUES (12, 12);
INSERT INTO customer VALUES (13, 13);
INSERT INTO customer VALUES (14, 14);
INSERT INTO customer VALUES (15, 15);
INSERT INTO customer VALUES (16, 16);
INSERT INTO customer VALUES (17, 17);
INSERT INTO customer VALUES (18, 18);
INSERT INTO customer VALUES (19, 19);
INSERT INTO customer VALUES (20, 20);

------------- admin -------------
INSERT INTO admin VALUES (1);
INSERT INTO admin VALUES (2);
INSERT INTO admin VALUES (3);
INSERT INTO admin VALUES (4); 

------------- product -------------
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (1, 'Mens Blue Jeans', 49.99, 'M', 50, 'FashionCo', 4.7, 'Classic blue jeans for men');
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (2, 'Womens Red Dress', 39.99, 'S', 30, 'EleganceWear', 4.5, 'Elegant red dress for women');
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (3, 'Mens White Shirt', 29.99, 'L', 60, 'FashionCo', 4.6, 'Crisp white shirt for men');
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (4, 'Womens Denim Jacket', 59.99, 'M', 40, 'CasualWear', 4.4, 'Stylish denim jacket for women');
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (5, 'Mens Sneakers', 69.99, '9', 25, 'SportyShoes', 4.8, 'Comfortable sneakers for men');
INSERT INTO product (id_product, name, price, size, stock, brand, rating, description)
VALUES (6, 'Womens Summer Skirt', 34.99, 'M', 45, 'CasualWear', 4.3, 'Light and breezy summer skirt for women');

------------- discount -------------
INSERT INTO discount (id_discount, amount, start_date, end_date)
VALUES (1, 0.10, '2023-11-01', '2023-12-31');
INSERT INTO discount (id_discount, amount, start_date, end_date)
VALUES (2, 0.20, '2023-11-01', '2023-12-31');
INSERT INTO discount (id_discount, amount, start_date, end_date)
VALUES (3, 0.15, '2023-11-01', '2023-12-31');
INSERT INTO discount (id_discount, amount, start_date, end_date)
VALUES (4, 0.30, '2023-11-01', '2023-12-31');
INSERT INTO discount (id_discount, amount, start_date, end_date)
VALUES (5, 0.50, '2023-11-01', '2023-12-31');

------------- review -------------
INSERT INTO review (id_review, review_text, rating, id_customer, id_product)
VALUES (1, 'Great product! Fits perfectly.', 5, 5, 1);
INSERT INTO review (id_review, review_text, rating, id_customer, id_product)
VALUES (2, 'Excellent quality. Fast delivery.', 4, 6, 1);
INSERT INTO review (id_review, review_text, rating, id_customer, id_product)
VALUES (3, 'Comfortable and stylish.', 5, 7, 2);
INSERT INTO review (id_review, review_text, rating, id_customer, id_product)
VALUES (4, 'Good value for the price.', 4, 8, 2);
INSERT INTO review (id_review, review_text, rating, id_customer, id_product)
VALUES (5, 'Highly recommended!', 5, 9, 3);

------------- cart_product -------------
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (5, 1, 2);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (6, 1, 1);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (7, 2, 3);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (8, 2, 2);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (9, 3, 1);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (10, 4, 5);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (11, 4, 1);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (12, 5, 3);
INSERT INTO cart_product (id_cart, id_product, quantity)
VALUES (13, 6, 1);

------------- address -------------
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (1, '123 Main St', 456, '12345', 'USA');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (2, '456 Elm St', 789, '67890', 'Canada');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (3, '789 Oak St', 1011, '34567', 'UK');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (4, '1012 Pine St', 1314, '23456', 'Australia');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (5, '1315 Cedar St', 1516, '45678', 'Germany');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (6, '123 Maple St', 111, '56789', 'USA');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (7, '456 Birch St', 222, '67890', 'Canada');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (8, '789 Oak St', 333, '78901', 'UK');
INSERT INTO address (id_address, street_name, street_number, zipcode, country)
VALUES (9, '1012 Pine St', 444, '89012', 'Australia');

------------- payment_method -------------
INSERT INTO payment_method VALUES (1);
INSERT INTO payment_method VALUES (2);
INSERT INTO payment_method VALUES (3);
INSERT INTO payment_method VALUES (4);
INSERT INTO payment_method VALUES (5);
INSERT INTO payment_method VALUES (6);
INSERT INTO payment_method VALUES (7);
INSERT INTO payment_method VALUES (8);
INSERT INTO payment_method VALUES (9);

------------- purchase -------------
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-01-15', '2023-01-20', 'Processing', 5, 1, 1, 5);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-02-10', '2023-02-15', 'Packed', 6, 2, 2, 6);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-03-05', '2023-03-10', 'Shipped', 7, 3, 3, 7);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-04-20', '2023-04-25', 'Delivered', 8, 4, 4, 8);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-05-10', '2023-05-15', 'Processing', 9, 5, 5, 9);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-06-03', '2023-06-08', 'Shipped', 10, 6, 6, 10);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-07-18', '2023-07-23', 'Processing', 11, 7, 7, 11);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-08-15', '2023-08-20', 'Delivered', 12, 8, 8, 12);
INSERT INTO purchase (order_date, delivery_date, order_status, id_customer, id_address, id_payment_method, id_cart)
VALUES ('2023-09-25', '2023-09-30', 'Processing', 13, 9, 9, 13);

------------- paypal -------------
INSERT INTO paypal (id_paypal, email)
VALUES (1, 'paypaluser1@example.com');
INSERT INTO paypal (id_paypal, email)
VALUES (2, 'paypaluser2@example.com');
INSERT INTO paypal (id_paypal, email)
VALUES (3, 'paypaluser3@example.com');

------------- card -------------
INSERT INTO card (id_card, number, exp_date, cvv)
VALUES (4, 1234567890123456, '2025-12-31', 123);
INSERT INTO card (id_card, number, exp_date, cvv)
VALUES (5, 9876543210987654, '2026-06-30', 456);
INSERT INTO card (id_card, number, exp_date, cvv)
VALUES (6, 1111222233334444, '2024-09-30', 789);

------------- transfer -------------
INSERT INTO transfer (id_transfer, entity, reference, valid)
VALUES (7, 987654321, 1234567890, 24);
INSERT INTO transfer (id_transfer, entity, reference, valid)
VALUES (8, 111122223, 9999888877, 48);
INSERT INTO transfer (id_transfer, entity, reference, valid)
VALUES (9, 777766665, 5555444433, 72);

------------- notification -------------
INSERT INTO notification (id_notification , content, seen, id_customer)
VALUES (1, 'New promotion available!', false, 5);
INSERT INTO notification (id_notification, content, seen, id_customer)
VALUES (2, 'Your order has been shipped.', false, 6);
INSERT INTO notification (id_notification, content, seen, id_customer)
VALUES (3, 'Special offer on selected products.', false, 7);
INSERT INTO notification (id_notification, content, seen, id_customer)
VALUES (4, 'Product back in stock!', false, 5);
INSERT INTO notification (id_notification, content, seen, id_customer)
VALUES (5, 'Upcoming sale event.', false, 6);

------------- wishlist -------------
INSERT INTO wishlist (id_customer, id_product)
VALUES (5, 4);
INSERT INTO wishlist (id_customer, id_product)
VALUES (6, 2);
INSERT INTO wishlist (id_customer, id_product)
VALUES (7, 5);
INSERT INTO wishlist (id_customer, id_product)
VALUES (8, 3);
INSERT INTO wishlist (id_customer, id_product)
VALUES (9, 1);

------------- faq -------------
INSERT INTO faq (id_faq, question, answer)
VALUES (1, 'How can I return a product?', 'You can return a product by contacting our customer support and following our return policy.');
INSERT INTO faq (id_faq, question, answer)
VALUES (2, 'What are the shipping options?', 'We offer standard and express shipping options. The delivery times and costs may vary.');
INSERT INTO faq (id_faq, question, answer)
VALUES (3, 'Do you offer international shipping?', 'Yes, we provide international shipping to many countries. Shipping fees may apply.');
INSERT INTO faq (id_faq, question, answer)
VALUES (4, 'How can I track my order?', 'You can track your order by logging into your account and accessing the order tracking feature.');
INSERT INTO faq (id_faq, question, answer)
VALUES (5, 'What is your return policy?', 'Our return policy allows returns within 30 days of purchase. Please review our policy for details.');

