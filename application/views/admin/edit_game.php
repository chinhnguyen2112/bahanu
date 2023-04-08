<style>
    .form_change_pass {
        width: 800px;
        margin: auto;
    }

    .error {
        font-size: 14px;
        color: red;
    }

    .label {
        width: 100%;
    }
    .form-control .avt_game {
        margin-right: 10px;
    }
    .form-edit-img {
        height: max-content;
    }

    @media only screen and (max-width: 1024px) {
        .form_change_pass {
            width: 100%;
        }
    }
    @media only screen and (max-width: 375px) {
        .form-edit-img {
            text-align: center;
        }
        .form-edit-img .avt_game {
            margin-bottom: 10px;
        }
    }
</style>
<link rel="stylesheet" href="/assets/css/sweetalert.css">

<form id="form" class="form_change_pass">
   
    <input type="hidden" name="id" hidden value="<?= isset($id) ? $id : ''  ?>" />
    <div class="form-group">
        <label>Tên</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?= isset($id) ? $account['name'] : '' ?>" >
    </div>

    <div class="form-group">
        <label>Ảnh</label>
        <!-- <input type="text" class="form-control" id="name" name="name" placeholder="usrename" value="<?= ($id > 0) ? $account['name'] : '' ?>"> -->
        <div class="form-control form-edit-img">
            <img id="mainImage" class="avt_game" src="/<?= isset($id) ? $account['image'] : 'images/avt.png' ?>" style="width:100px;height:100px">
            <input  type="file" id="img_update" name="img_update" onchange="document.getElementById('mainImage').src = window.URL.createObjectURL(this.files[0])">
        </div>
    </div>
    
    
    <div class="form-group">
        <button type="submit"  class="form-control btn btn-primary submit px-3"><?= (isset($id)) ? "Sửa" : "Thêm mới" ?></button>
    </div>

</form>

<script src="/assets/js/jquery.validate.min.js"></script>

<script>
    $("#form").validate({
        onclick: false,
        rules: {
            name: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Vui lòng nhập tên hiển thị",
            }
        },

        submitHandler: function(form) {
            var formData = new FormData($('#form')[0]);
            $.ajax({
                url: '/admin/add_new_game',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                data: formData,
                success: function(response) {
                    if (response.status == 1) {
                        swal({
                            title: "Thành Công",
                            type: "success",
                            text: response.msg
                        }, function() {
                            window.location = '/admin/list_game';
                        });
                    } else {
                        swal({
                            title: "Thất bại",
                            type: "error",
                            text: response.msg
                        });
                    }
                },
                error: function(xhr) {
                    alert('Thất bại');
                }
            });
            return false;
        }
    });
</script>
</body>

</html>