<ul class="nav flex-column p-3">

    @foreach ($links as $link)

        <li class="nav-item">
            <a class="nav-link {{$link['activ_class']}}"
             aria-current="{{$link['aria-current']}}"
             href="{{$link['href']}}"
            >{{$link['text']}}</a>
        </li>

    @endforeach

</ul>
