<div id="main">
    <div class="content">
        <!-- banner -->
        <div class="banner">
            <div class="slider">
                <img src="/images/quanly/slider.png" alt="slider-img">
            </div>

            <div class="header-content">
                <div class="simple-info">
                    <img class="avt_main" src="/<?= $_SESSION['user']['avatar'] ?>" alt="avt1">
                    <div class="text">
                        <h2> <?php echo $_SESSION['user']['name']; ?> </h2>

                        <p>
                            <?php
                            if ($_SESSION['user']['email'] != "") {
                                echo $_SESSION['user']['email'];
                            } else {
                                echo 'Chưa cập nhật';
                            }
                            ?>
                        </p>
                        <div class="money">
                            <img src="/assets/quanly/coins 1.png" alt="Zen">
                            <p> Số dư: <span> <?php echo number_format($_SESSION['user']['zen']); ?> Zen </span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- main-content -->
        <div class="main-content">

            <!-- main info -->
            <div class="box-title">
                <div class="title">
                    <li class="title-li hien active-info-box">
                        <img class="dot-green appear" src="/images/quanly/dot-green.svg" alt="dot-green">
                        <span>Thông tin cơ bản</span>
                    </li>

                    <li class="title-li none-on-respon">
                        <img class="dot-green" src="/images/quanly/dot-green.svg" alt="dot-green">
                        <a href="/nap-the/" target="_blank">Nạp thẻ</a>
                    </li>

                    <li class="title-li active-change-pass none-on-respon">
                        <img class="dot-green" src="/images/quanly/dot-green.svg" alt="dot-green">
                        <span>Đổi mật khẩu</span>
                    </li>
                    <span class="a-arrow-down">
                        <img src="/images/quanly/arrow-down.svg" alt="arrow-down">
                    </span>

                </div>

                <!-- menu li -->
                <div class="menu-li">
                    <select name="" id="" onchange="" class="menu-select">
                        <option value="1">Thông tin cơ bản</option>
                        <!-- <option value="2">Lịch sử chơi game</option>
                        <option value="3">Rút thưởng</option> -->
                        <option value="4">Nạp thẻ</option>
                        <option value="5">Đối mật khẩu</option>
                        <!-- <option value="6">Kho đồ</option>
                        <option value="7">Mã giới thiệu</option>
                        <option value="8">Đổi Zen</option> -->
                    </select>
                </div>
            </div>

            <!-- body info -->
            <div class="body-info active">
                <form id="update_info" enctype="multipart/form-data">
                    <div class="box-info">
                        <div class="box-left">
                            <div class="row">
                                <label for="">
                                    <p>HỌ VÀ TÊN</p>
                                </label>
                                <input type="text" name="fullname" id="fullname" placeholder="Tên của bạn" value="<?php echo $_SESSION['user']['name']; ?>">
                            </div>

                            <div class="row">
                                <label for="">
                                    <p>TÊN TÀI KHOẢN</p>
                                </label>
                                <input type="text" name="" placeholder="Tên tài khoản" disabled id="" value="<?php echo $_SESSION['user']['username']; ?>">
                            </div>

                            <div class="row-special">
                                <label for="">
                                    <p>GIỚI TÍNH</p>
                                </label>
                                <div class="choose-sex">
                                    <div class="male">
                                        <input type="checkbox" class="check_gt" data-id="1" name="sex" <?php
                                                                                                        if ($_SESSION['user']['sex'] == 1) {
                                                                                                            echo 'checked';
                                                                                                        } ?>>
                                        <p>Nam</p>
                                    </div>

                                    <div class="female">
                                        <input type="checkbox" class="check_gt" data-id="2" name="sex" <?php
                                                                                                        if ($_SESSION['user']['sex'] == 2) {
                                                                                                            echo 'checked';
                                                                                                        } ?>>
                                        <p>Nữ</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="" id="sex" value="<?php echo $_SESSION['user']['sex'] ?>">
                            <div class="row">
                                <label for="">
                                    <p>EMAIL</p>
                                </label>
                                <input type="email" name="email" id="email" placeholder="Email của bạn" value="<?php
                                                                                                                if ($_SESSION['user']['email'] != "") {
                                                                                                                    echo $_SESSION['user']['email'];
                                                                                                                }
                                                                                                                ?>">
                            </div>

                            <div class="row">
                                <label for="">
                                    <p>SỐ ĐIỆN THOẠI</p>
                                </label>
                                <input type="text" name="phonenumber" id="phonenumber" placeholder="Số điện thoại của bạn" value="<?php
                                                                                                                                    if ($_SESSION['user']['phone'] != null) {
                                                                                                                                        echo $_SESSION['user']['phone'];
                                                                                                                                    } ?>">
                            </div>

                            <div class="row">
                                <label for="">
                                    <p>NGÀY SINH</p>
                                </label>
                                <input type="date" name="birthday" id="birthday" placeholder="Ngày sinh của bạn" value="<?php
                                                                                                                        if ($_SESSION['user']['birthday'] != "") {
                                                                                                                            echo date('Y-m-d', $_SESSION['user']['birthday']);
                                                                                                                        } ?>">
                            </div>

                            <div class="row ">
                                <label for="">
                                    <p>ĐỊA CHỈ</p>
                                </label>
                                <input type="text" name="address" id="address" placeholder="Địa chỉ của bạn" value="<?php
                                                                                                                    if ($_SESSION['user']['address'] != "") {
                                                                                                                        echo $_SESSION['user']['address'];
                                                                                                                    } ?>">
                            </div>

                        </div>

                        <div class="box-right">
                            <div class="avatar">
                                <div class="avatar-img">
                                    <h3>ẢNH ĐẠI DIỆN</h3>
                                    <img src="/<?php if (isset($_SESSION['user']['avatar']) && $_SESSION['user']['avatar'] !== "") {
                                                    echo   $_SESSION['user']['avatar'];
                                                } else {
                                                    echo 'images/quanly/avt2.png';
                                                }
                                                ?>" class="img_avt" alt="avt2">
                                </div>
                                <div class="text-avatar">
                                    <p class="upload_btn">
                                        Đăng tải ảnh đại diện của bạn
                                    </p>
                                    <input onchange="preview_image(event,this)" type="file" name="avatar" id="avatar" hidden>
                                    <span>Tải lên ảnh từ thiết bị của bạn. Ảnh nên cắt thành hình vuông, kích thước ít nhất 184px x 184px.</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="last-row">
                        <div class="last-btn">
                            <!-- <button class="cancel-btn">Hủy bỏ</button> -->
                            <button class="save-btn">Lưu</button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- change pass page -->

            <div class="box_change_pass">
                <div class="change_pass_container">
                    <form id="change_pass">
                        <div class="row_change_pass">
                            <label for="">
                                <p>MẬT KHẨU CŨ</p>
                            </label>

                            <input type="password" name="old_pass" id="old_pass" value="">
                        </div>

                        <div class="row_change_pass">
                            <label for="">
                                <p>MẬT KHẨU MỚI</p>
                            </label>

                            <input type="password" id="new_pass" name="new_pass" value="">
                        </div>

                        <div class="row_change_pass">
                            <label for="">
                                <p>XÁC NHẬN MẬT KHẨU MỚI</p>
                            </label>

                            <input type="password" name="re_pass" value="">

                        </div>

                        <div class="change_pass_btn">
                            <div class="last-btn">
                                <!-- <button class="cancel-btn">Hủy bỏ</button> -->
                                <button class="save-btn">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        </div>
    </div>
</div>
<!-- thông báo chọn voucher -->
<div class="box_noti_gif popup_voucher">
    <div class="body_gif">
        <div class="title_noti_gif">
            <span class="noti_title_gif">THẤT BẠI</span>
        </div>
        <div class="body_noti_gif">
            <div class="body_gif_nav">
                <img class="img_error" src="/images/quanly/error.svg" alt="lỗi">
                <div class="div_img_noti">
                    <div class="box_left_img">
                        <div class="box_img_free">
                            <img class="img-small" src="/images/quanly/star.svg" alt="chúc mừng">
                        </div>
                        <div class="box_img_free">
                            <img class="img_big" src="/images/quanly/star.svg" alt="chúc mừng">
                        </div>
                    </div>
                    <div class="box_left_img">
                        <div class="box_img_free">
                            <img class="img_big" src="/images/quanly/star.svg" alt="chúc mừng">
                        </div>
                        <div class="box_img_free">
                            <img class="img-small" src="/images/quanly/star.svg" alt="chúc mừng">
                        </div>
                    </div>
                </div>
                <div class="box_text_noti_gif">
                    <div class="type_gif">
                        <p class="happy_gif">Bạn chưa đăng nhập. Vui lòng đăng nhập!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="box_close_gif">
            <span class="span_close_gif" onclick="$('.popup_voucher').hide();">Đóng</span>
            <span class="span_close_gif btn_lg" style="background: #e67300 " onclick="window.location.href = '/vong-quay-lien-quan/'">Đến vòng quay</span>
        </div>
    </div>
</div>