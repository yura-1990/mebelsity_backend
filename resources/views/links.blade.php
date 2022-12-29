<ul class="list-unstyled ">
    <li><a class="{{ request()->routeIs('allmebel.index') ? "text-danger": "" }}  nav-link admin-link fs-5 py-2 mebels"   href="{{ route('allmebel.index') }}">Набор Оффисныx мебелов</a></li>
    <li><a class="{{ request()->routeIs('mebel.index') ? "text-danger": "" }} nav-link admin-link fs-5 py-2 mebels"   href="{{ route('mebel.index') }}">Оффисные мебели</a></li>
    <li><a class=" {{ request()->routeIs('loftmebel.index') ? "text-danger": "" }} nav-link admin-link fs-5 py-2 mebels"   href="{{ route('loftmebel.index') }}">Оффисные мебели в стили LOFT</a></li>
    <li><a class=" {{ request()->routeIs('softmebel.index') ? "text-danger": "" }} nav-link admin-link fs-5 py-2 mebels"   href="{{ route('softmebel.index') }}">Набор мягкого мебели</a></li>
    <li>
        <a class=" {{ request()->routeIs('kitchenmebel.index') ? "text-danger": "" }} nav-link admin-link fs-5 py-2 mebels"   href="{{ route('kitchenmebel.index') }}">Набор кухонных мебелей</a>
    </li>
    <li>
        <a class=" {{ request()->routeIs('homemebel.index') ? "text-danger": "" }} nav-link admin-link fs-5 py-2 mebels"  href="{{ route('homemebel.index') }}">Мебели для дома</a>
    </li>
</ul>

