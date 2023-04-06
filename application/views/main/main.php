<div class="content_page">
    <div class="body_content">
        <div class="left_page">
            <div class="box_cate">
                <p class="title_cate">Các loại game</p>
                <div class="box_list_cate">
                    <ul class="list_cate_game">
                        <?php
                        foreach ($list_game as $val) { ?>
                            <li class="list__game">
                                <img src="/<?= $val['image'] ?>" alt="<?= $val['name'] ?>">
                                <p class="name_game"><?= $val['name'] ?></p>
                            </li>
                        <?php  }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
        <div class="center_page">
            <!-- <p class="title_box">Danh sách Playdoul</p> -->
            <div class="list_kol">
                <?php $CI = get_instance();
                foreach ($list_kol as $val) {
                    $list_img_cate = '';
                    $i = "";
                    if ($val['cate'] != '') {
                        $arr = explode(',', $val['cate']);
                        $i = "";
                        foreach ($arr as $key2 =>  $val2) {
                            if ($key2 < 4) {
                                $cate = $CI->Account->query_sql_row("SELECT * FROM category WHERE id = $val2 ");
                                $list_img_cate .= '<img src="./' . $cate['image'] . '" alt="' . $cate['name'] . '">';
                            } else {
                                $i = '<p class="hide_cate">+' . ($key2 - 3) . '</p>';
                            }
                        }
                    } ?>
                    <div class="box_list_danhmuc">
                        <a href="/idol-<?= $val['id'] ?>/">
                            <div class="box_detail_danhmuc">
                                <img src="/<?= $val['avatar'] ?>" alt="<?= $val['name'] ?>">
                                <p class="num_count"><?= number_format($val["price"], 0, '.', '.') ?> Zen/giờ</p>
                            </div>

                            <div class="box_detail_mobile">
                                <p class="name_mobile"><?= $val['name'] ?></p>
                                <p class="intro_mobile"><?= $val['text_intro'] ?></p>

                                <div class="box_data_detail_mobile">
                                    <div class="list_cate_mobile">
                                        <?= $list_img_cate . $i ?>
                                    </div>
                                    <div class="list_star_mobile">
                                        <img src="/images/star.png" atl="đánh giá sao">
                                        <p class="p_avg_star">0</p>
                                        <p class="p_count_amount">(0)</p>
                                    </div>
                                </div>
                            </div>


                            <p class="name"><?= $val['name'] ?></p>
                            <p class="intro"><?= $val['text_intro'] ?></p>
                            <div class="box_data_detail">
                                <div class="list_cate">
                                    <?= $list_img_cate . $i ?>
                                </div>
                                <div class="list_star">
                                    <img src="/images/star.png" atl="đánh giá sao">
                                    <p class="p_avg_star">0</p>
                                    <p class="p_count_amount">(0)</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <?php if ($list_kol == null) {
                echo '<p class="no_data">Danh sách trống.</p>';
            } else { ?><a class="show_all" href="/danh-sach-idol/">Xem tất cả</a>
            <?php } ?>
        </div>
        <div class="right_page" id="right_page">
            <div id="btn">
                <span style="cursor:pointer" onclick="openList();">
                    <div class="btn-close" onclick="closeList(this)">
                        <p>Bạn bè</p>

                    </div>
                </span>
            </div>
            <div class="list_user list_user_right" id="list__user">
                <?php if ($my_friend == null && check_login()) {
                    echo '<p class="no_data">Chưa có bạn bè</p>';
                } elseif (!check_login()) {
                    echo '<p class="no_data">Chưa đăng nhập</p>';
                } else {
                    foreach ($my_friend as $val) { ?>
                        <div class="this_user_list">
                            <img src="/<?= $val['avatar'] ?>" alt="<?= $val['name'] ?>">
                            <div class="detail_user">
                                <p class="name_user"><?= $val['name'] ?></p>
                                <div class="list_box_user" data-id="<?= $val['id'] ?>">
                                    <p class="btn_user" onclick="check_thaotac(this,'<?= $val['id'] ?>','del')">Xóa bạn bè</p>
                                    <a class="btn_user chat_user" href="/message?user=<?= $val['id'] ?>">Nhắn tin</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <p class="load_more" onclick="show_friend(this,2)">Xem thêm</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>