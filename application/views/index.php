<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <?php if (isset($index) && $index == 1) { ?>
    <meta name="robots" content="index,follow" />
  <?php } else { ?>
    <meta name="robots" content="noindex,nofollow" />
  <?php } ?>
  <title><?= isset($meta_title) ? $meta_title : '' ?></title>
  <meta content="<?= isset($meta_title) ? $meta_title : '' ?>" name="description">
  <meta content="<?= isset($meta_title) ? $meta_title : '' ?>" name="msvalidate.01">
  <meta name="keywords" content="<?= isset($meta_keyword) ? $meta_keyword : "" ?>" />
  <link rel="canonical" href="<?= (isset($canonical)) ? $canonical : "" ?>" />
  <meta property="og:locale" content="vi_VN" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= (isset($canonical)) ? $canonical : "" ?>" />
  <meta property="og:title" content="<?= isset($meta_title) ? $meta_title : '' ?>" />
  <meta property="og:image" content="<?= isset($img_head) ? $img_head : base_url() . 'assets/img_blog/photo_2022-03-04_17-33-06.jpg'  ?>" />
  <meta property="og:image:secure_url" content="<?= isset($img_head) ? $img_head : base_url() . 'assets/img_blog/photo_2022-03-04_17-33-06.jpg'  ?>" />
  <meta property="og:site_name" content="Shop Liên Quân mobile - Free Fire giá cực hấp dẫn" />
  <meta property="og:description" content="<?= isset($meta_title) ? $meta_title : '' ?>" />
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:description" content="<?= isset($meta_title) ? $meta_title : '' ?>" />
  <meta name="twitter:title" content="<?= isset($meta_title) ? $meta_title : '' ?>" />
  <link rel="shortcut icon" href="<?= base_url() ?>images/fav.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <link data-n-head="ssr" rel="icon" type="image/x-icon" href="<?= base_url() ?>images/fav.png">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/sweetalert.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/oneui.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css?v=1">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/css2.css?v=1">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/popup.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/sanacc/css_popup.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/sanacc/css_header_new.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/sanacc/css_footer.css?v=1">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/new_footer.css?v=<?= time() ?>">

  <?php if (isset($list_css)) {
    foreach ($list_css as $css) { ?>
      <link rel="stylesheet" href="<?= base_url(); ?>assets/css/<?= $css ?>">
      </link>
  <?php  }
  } ?>
</head>

<body>
  <?php
  $this->load->view("includes/header");

  ?>
  <?php
  if (isset($content)) {
    $this->load->view($content);
  }

  $this->load->view("includes/footer");
  ?>
  <div class="notify_auto">
    <div class="content_notify">
      <div class="body_notify">
        <img src="/images/fav.png" alt="Logo tạm">
        <p>Bạn có tin nhắn mới.<br>Vui lòng kiểm tra</p>
      </div>
    </div>
    <img class="img_close_tb" onclick="hide_tb(this)" src="/images/icon_close.svg" alt="tắt thống báo">
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.4/socket.io.js"></script>
  <script>
    var socket = io.connect('https://chat-nodejs-t8v9.onrender.com', {
      enabledTransports: ["https"],
      transports: ['websocket', 'polling']
    });
    socket.on('new_message', function(data) {
      $('.notify_auto').show()
    })

    function hide_tb(e) {
      $('.notify_auto').hide(400);
    }
    $('.body_notify').click(function() {
      window.location.href = "/message/";
    })
  </script>
</body>

</html>