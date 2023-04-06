<div class="header">
    <div class="box_header">
        <div class="header_logo">
            <a href="/"><img src="/images/logo.png" class="header_logo_pc " alt="Logo sanacc"></a>
        </div>
        <div class="box_header box_list_menu header_list_pc">
            <div class="list_menu">
                <div class="box_menu">
                    <a href="/"><img src="/images/header/home.png" title="trang chủ"></a>
                </div>
                <div class="box_menu ">
                    <a href="/danh-sach-idol/"><img src="/images/header/Vector.png" title="Cửa hàng"></a>
                </div>
                <div class="box_menu">
                    <img src="/images/header/category-2.png" alt="dịch vụ">
                    <img class="img_down box_btn_header_mb" src="/images/arrow-bottom.png" title="xem thêm">
                    <div class="menu_con">
                        <p><span><a href="/the-game-garena/"><img class="img_icon_game " src="/images/home/garena.png" alt="game liên quân mobile"><span class="menu_span">Mua thẻ</span><img class="img_goto_link " src="/images/arrow-right.svg" alt="đi tới"></a></span></p>
                    </div>
                </div>
                <div class="box_menu">
                    <a href="/nap-the/"><img src="/images/header/card-pos.png" title="nạp thẻ"></a>
                </div>
            </div>
        </div>
        <div class="list_header">
            <div class="list_nav_header">
                <span class="text_box_header hide_mb"><a href="/message/"><img src="/images/chat.svg" alt="tin nhắn"> Tin nhắn</a></span>
                <?php if (check_login()) { ?>
                    <div class="header_btn data_mb box_btn_header_pc">
                        <img class="img_avatar_user" src="<?= '/' . $_SESSION['user']['avatar'] ?>">
                        <span class="list_data_u">
                            <p class="total_zen">Zen: <?= number_format($_SESSION['user']['zen']) ?> </p>
                        </span>
                        <div class="menu_con">
                            <p class="menu_p"><span><a href="/quan-ly-tai-khoan/"><span class="menu_span">Quản lý tài khoản</span></a></span></p>
                            <p class="menu_p"><span><a href="/lich-su-choi/"><span class="menu_span">Lịch sử chơi</span></a></span></p>
                            <p class="menu_p"><span><a href="/lich-su-nap-the/"><span class="menu_span">Lịch sử mua hàng</span></a></span></p>
                            <p class="menu_p"><span><a href="/logout/"><span class="menu_span">Đăng xuất</span></a></span></p>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="box_btn_header box_btn_header_pc">
                        <span class="text_login"><a href="/dang-ky/">Đăng ký</a></span>
                    </div>
                    <div class="box_btn_header box_btn_header_pc">
                        <span class="text_login"><a href="/dang-nhap/">Đăng nhập</a></span>
                    </div>
                <?php } ?>
                <div class="menu_mb header_logo_tablet  box_btn_header_mb " onclick="$('.header_list_tablet').show(100)">
                    <img src="/images/header/menu_con.png" alt="menu">
                </div>
            </div>
        </div>
    </div>

    <div class="box_header box_list_menu header_list_tablet">
        <div class="box_logo_mb box_btn_header_mb">
            <img src="/images/logo.png" class="header_logo_pc header_logo_mb  " alt="Logo">
            <img src="/images/header/close.png" class="close_menu_mb header_logo_mb  " alt="Logo" onclick="$('.header_list_tablet').hide(100)">
        </div>
        <div class="list_menu">
            <?php if (check_login()) { ?>
                <div class="header_btn data_mb" onclick="click_menu_con(this,1)">
                    <img class="img_avatar_user" src="<?= $_SESSION['user']['avatar'] ?>">
                    <span class="list_data_u">
                        <p class="total_zen">Zen: <?= number_format($_SESSION['user']['zen']) ?></p>
                    </span>
                </div>
                <div class="menu_con">
                    <p class="menu_p"><span><a href="/quan-ly-tai-khoan/"><span class="menu_span">Quản lý tài khoản</span></a></span></p>
                    <p class="menu_p"><span><a href="/lich-su-choi/"><span class="menu_span">Lịch sử chơi</span></a></span></p>
                    <p class="menu_p"><span><a href="/lich-su-nap-the/"><span class="menu_span">Lịch sử mua hàng</span></a></span></p>
                    <p class="menu_p"><span><a href="/logout/"><span class="menu_span">Đăng xuất</span></a></span></p>
                </div>
            <?php } else { ?>
                <div class="list_btn_header box_btn_header_mb header_btn">
                    <div class="box_btn_header ">
                        <span class="text_login"><a href="/dang-ky/">Đăng ký</a></span>
                    </div>
                    <div class="box_btn_header ">
                        <span class="text_login"><a href="/dang-nhap/">Đăng nhập</a></span>
                    </div>
                </div>
            <?php } ?>
            <div class="box_menu">
                <img src="/images/chat.svg" alt="tin nhắn">
                <span><a href="/message/">Tin nhắn</a></span>
            </div>
            <div class="box_menu">
                <img src="/images/header/home.png" alt="trang chủ">
                <span><a href="/">Trang chủ</a></span>
            </div>
            <div class="box_menu ">
                <img src="/images/header/Vector.png" alt="Cửa hàng">
                <span><a href="/danh-sach-idol/">Danh sách Playdoul</a></span>
            </div>
            <div class="box_menu" onclick="click_menu_con(this,1)">
                <img src="/images/header/category-2.png" alt="dịch vụ">
                <span>Dịch vụ</span>
                <img class="img_down box_btn_header_mb" src="/images/arrow-bottom.png" alt="xem thêm">
            </div>
            <div class="menu_con">
                <p>
                    <span>
                        <a href="/the-game-garena/">
                            <img class="img_icon_game " src="/images/home/garena.png" alt="game liên quân mobile">
                            <span class="menu_span">Mua thẻ Garena</span>
                            <img class="img_goto_link " src="/images/arrow-right.svg" alt="đi tới">
                        </a>
                    </span>
                </p>
            </div>
            <div class="box_menu">
                <img src="/images/header/card-pos.png" alt="nạp thẻ">
                <span><a href="/nap-the/">Nạp thẻ</a></span>
            </div>
        </div>
    </div>
</div>