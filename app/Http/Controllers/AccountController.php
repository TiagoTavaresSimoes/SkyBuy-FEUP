public function index()
{
    
    $user = Auth::user();
    return view('account', ['user' => $user]);
}
