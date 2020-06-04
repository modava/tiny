<?php

use yii\helpers\Html;

$this->registerCSs('
@media (min-width: 992px) {
    .modal-lg, .modal-xl {
        max-width: 1050px;
        height: 100px;
    }
}
.img-select-content {
    position: relative;
    display: inline-block;
}
.has-img .remove-img {
    opacity: 1;
    z-index: 1;
}
.remove-img {
    position: absolute;
    color: red;
    border: solid 1px red;
    height: 20px;
    width: 20px;
    align-items: center;
    justify-content: center;
    top: -5px;
    right: -5px;
    display: flex;
    opacity: 0;
    z-index: -1;
    cursor: pointer;
    background: #fff;
    border-radius: 3px;
}
.upload-img-zone {
    z-index: 2;
    background: url(https://cdn3.iconfinder.com/data/icons/glypho-generic-icons/64/action-upload-alt-512.png) center center;
    background-size: contain;
}
.upload-img-zone, .upload-img-zone:before {
    content: "";
    position: absolute;
    top: 30%;
    left: 30%;
    right: 30%;
    bottom: 30%;
    z-index: -1;
    opacity: 0;
    transition: all .4s ease;
}
.image-modal {
    height: 150px;
    width: 150px;
    padding: .25rem;
    background-color: #f5f7fa;
    border: 1px solid #ddd;
    border-radius: .25rem;
}
.img-select:hover .upload-img-zone, .img-select:hover .upload-img-zone:before {
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
    opacity: 1;
}
');
$img = $path.$image;
?>
    <section class="hk-sec-wrapper"><label><?=$label; ?></label>
        <div class="row">
            <div class="col-sm">

                <div class="img-select-content<?= $image != null ? ' has-img' : '' ?>">
                    <div class="img-select" data-toggle="modal" data-target="#file-manager">
                        <?php if ($image == null || $image == '') {
                            $img = $path.NOIMAGE;
                            ?>
                            <img class="image-modal img-show" src="<?= $img ?>">
                            <?php
                        } else {
                            ?>
                            <img class="image-modal img-show"
                                 src="<?= $img ?>">
                            <?php
                        }
                        ?>
                        <div class="upload-img-zone">
                        </div>
                    </div>
                    <span class="remove-img delete"><i class="fa fa-times"></i></span>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="file-manager" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLarge01" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= Yii::t('backend', 'File Manager'); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <iframe src="<?= $link; ?>/dialog.php?type=2&field_id=<?= $idName; ?>&lang=vi&akey=<?= $filemanager_access_key; ?>"
                                        style="width: 100%; height: 900px;"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal"><?= Yii::t('backend', 'Close'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$frontendUrl = Yii::getAlias('@frontendUrl');
$noimg = $path.NOIMAGE;

$js = <<< JS
function responsive_filemanager_callback(field_id){
    let v = $('#'+field_id).val() || '';
    if('' !== v){
        v = v.replace('$frontendUrl', '');
    }
     setImg(field_id, v);
}
function setImg(ipt_avatar, avatar = null){
    $('#'+ ipt_avatar).val(avatar !== null ? avatar : '');
    $('.img-show').attr('src', avatar !== null ? avatar : '$noimg');
    if(avatar !== null) $('.img-select-content').addClass('has-img');
    else $('.img-select-content').removeClass('has-img');
}
$('body').on('click', '.remove-img.delete', function(e){
    e.preventDefault();
    setImg('$idName', null);
    return false;
})
JS;

$this->registerJS($js, \yii\web\View::POS_END);