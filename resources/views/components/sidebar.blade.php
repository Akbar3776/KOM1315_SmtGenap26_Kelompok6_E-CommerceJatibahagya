<h4>Akun Saya</h4>
<ul class="list-group">
    <li class="list-group-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
        <a href="{{ route('cart.index') }}"
            class="text-decoration-none {{ request()->routeIs('cart.index') ? 'text-white' : '' }}">Keranjang</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
        <a href="#"
            class="text-decoration-none {{ request()->routeIs('orders.index') ? 'text-white' : '' }}">Pesanan
            Saya</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
        <a href="{{ route('profile.show') }}"
            class="text-decoration-none {{ request()->routeIs('profile.show') ? 'text-white' : '' }}">Profil</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('password.change') ? 'active' : '' }}">
        <a href="{{ route('password.change') }}"
            class="text-decoration-none {{ request()->routeIs('password.change') ? 'text-white' : '' }}">
            Kata Sandi
        </a>
    </li>

    {{-- <li class="list-group-item">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm w-100">Keluar</button>
        </form>
    </li> --}}
</ul>
