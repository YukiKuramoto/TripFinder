

// プロフィール画像選択時プレビュー操作
$('.profile-image-input').on('change', (e) => {
    const image = $(e.target.files)[0];
    const reader = new FileReader();

    if (e.target.files.length > 0) {
      reader.onload = (e) => {
        $('.preview-image').attr('src', e.target.result);
        $('.preview-image').removeClass('preview-hide');
        $('.preview-current-image').addClass('preview-hide');
        console.log(e.target.result);
      };
      reader.readAsDataURL(image);
    }else {
      $('.preview-image').addClass('preview-hide');
      $('.preview-current-image').removeClass('preview-hide');
    }
  }
);
