@foreach($productImages as $img)
<li class="radio_img" data-index="{{ $img->id }}">
    <label for="img_{{ $img->id }}">
        <input class="prod_radio_img" id="img_{{ $img->id }}" name="primary" type="radio" value="{{ $img->id }}" @if($img->main == 1) checked="checked" @endif />
        <span></span>
    </label>     
    <img src="{{ asset(ImageManager::getImagePath( $img->image_name, 133, 100, 'crop' )) }}"/>
    <a class="btn-remove-product-image"><img src="{{ asset('images/Close.png') }}"/></a>
</li>
@endforeach