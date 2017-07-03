<?php
$urls = [
    'current'       => current_url(),
    
    'additionalCss' => url("styles/iframe.css"),
    'additionalJs'  => url("scripts/pages/site/iframe.js"),
    'additionalVendorJs' => url("vendor/build-plugins.js"),
    
    'newToSession'  => route("site.new.session"),
    'publish'       => route("site.publish"),
    
    //create
    'toPageBuilder'     => route("site.builder"),
    'changePage'        => route("site.builder.change.page"),
    'updateItemContent' => route("site.builder.update.item"),
    'uploadImage'       => route("site.builder.upload.image"),
    'sortElements'      => route("site.builder.sort.element"),
    'updateElementSection' => route("site.builder.update.element.section"),
    'refreshElementSection'=> route("site.builder.refresh.element.section"),
    'deleteElement'     => route("site.builder.delete.element"),
    'updateElement'     => route("site.builder.update.element"),
    'updateMapElement'  => route("site.builder.update.map"),
    'updateFileElement' => route("site.builder.update.file"),
    'updateYoutubeElement' => route("site.builder.update.youtube"),
    
    //customize
    'updateThemeColor' => route("site.customize.update.color"),
    'updateTheme'   => route("site.customize.update.theme"),
    
    //pages
    'pageCreate'    => route("site.pages.create"),
    'pageEdit'      => route("site.pages.edit"),
    'pageDelete'    => route("site.pages.delete"),
    'pageStore'     => route("site.pages.store"),
    
    //store
    'updateCategoryStatus'  => route("site.store.category.update.status"),
    'getCategoryForm'       => route("site.store.category.form"),
    'getProductForm'        => route("site.store.product.form"),
    'getProductImages'      => route("site.store.product.images"),
    'uploadProductImage'    => route("site.store.product.upload.image"),
    'removeProductImage'    => route("site.store.product.remove.image"),
    'setMainProductImage'   => route("site.store.product.main.image"),
    
    //settings
    'uploadFavicon'     => route("site.settings.upload.favicon"),
    'updateStatus'      => route("site.settings.update.status"),
    'updateTitle'       => route("site.settings.update.title"),
    'updateMetaInfo'    => route("site.settings.update.meta"),
    'updateUrl'         => route("site.settings.update.url"),
    'checkSubdomain'    => route("site.settings.check.subdomain"),
    'checkPointDomain'  => route("site.settings.check.pointdomain"),
    'checkDomain'       => route("domain.ajax.check"),
];

$itemTypes = [
    'title'     => ITEM_TITLE,
    'paragraph' => ITEM_PARAGRAPH,
    'imagetext' => ITEM_IMAGE_TEXT,
    'image'     => ITEM_IMAGE,
    'contactforn' => ITEM_CONTACT_FORM,
    'gallery'   => ITEM_GALLERY,
    'map'       => ITEM_MAP,
    'slider'    => ITEM_SLIDER,
    'google'    => ITEM_GOOGLE_ADSENSE,
    'column'    => ITEM_COLUMN,
    'social'    => ITEM_SOCIAL_ICONS,
    'youtube'   => ITEM_YOUTUBE,
    'file'      => ITEM_FILE,
    'divider'   => ITEM_DIVIDER,
];
?>

<script>
url = $.parseJSON('{!! json_encode($urls) !!}');
itemTypes = $.parseJSON('{!! json_encode($itemTypes) !!}');
</script>

@if(isset($elements))
<script>
elements = $.parseJSON('{!! $elements !!}');
</script>
@endif

<script>
common.firstLoading = <?php echo (isset($firstLoading) && $firstLoading == true) ? 'true' : 'false'; ?>;
</script>