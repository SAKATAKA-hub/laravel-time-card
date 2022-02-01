<div class="dropdown">
    <a class="nav-link dropdown-toggle mb-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">


        @if (Auth::check())
            <span><strong>{{Auth::user()->name}}</strong> さん</span>
        @else
            <span><strong>ゲスト</strong> さん</span>
        @endif



    </a>

    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

        <li>
            <a class="dropdown-item" href="">
                <i class="bi bi-person"></i> マイページ
            </a>
        </li>

        @if (Auth::user()->app_dministrator)
        <li>
            <a class="dropdown-item" href="{{route('app_admin.top')}}">管理者ページ</a>
        </li>
        @endif

        <li>
            <a class="dropdown-item" href="{{route('edit_register')}}">ユーザー情報の変更</a>
        </li>

        <li>
            <a class="dropdown-item" href="{{route('edit_register')}}">退会する</a>
        </li>


        <li><hr class="dropdown-divider"></li>

        <li>
            <form method="POST" action="{{route('logout')}}" lass="dropdown-item">
                @csrf
                <button class="dropdown-item"><i class="bi bi-box-arrow-right"></i> ログアウト</button>
            </form>
        </li>


    </ul>
</div><!--end user menu -->
