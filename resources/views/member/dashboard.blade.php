<h1>Member Dashboard</h1>
<p>ยินดีต้อนรับ สมาชิก!</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button onclick="event.preventDefault(); this.closest('form').submit();">logout</button>

</form>