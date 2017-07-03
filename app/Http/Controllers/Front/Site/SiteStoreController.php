<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Core\FrontAuthenticatedController;
use App\Libraries\Template;

use App\Models\Campaign;
use App\Models\Site;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use App\Models\StoreOrder;
use App\Models\StoreShipping;

use Input, Request;

class SiteStoreController extends FrontAuthenticatedController {
    protected $menu = 'store';
    protected $pageTitle = 'My Sites';        
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->cart->isEmpty()) force_redirect(my_route('site.list'));
    }
    
    ////////////////////////////////////////////////////////////////
    // Action Methods
    ////////////////////////////////////////////////////////////////
    
    ////////////////////////////
    //dashboard
    /**
    * display store dashboard
    * 
    */
    public function index()
    {
        $site = $this->cart->getSite();
        
        $this->page = 'dashboard';    
        return $this->view('site.store.dashboard', compact('site'));   
    } 
    
    ////////////////////////////
    //category
    /**
    * display category list page
    * 
    */
    public function categories()
    {
        $site = $this->cart->getSite();
        
        //get orders
        $this->saveSorting('site.store.category.list');
        
        $order = Request::input('order');
        $orderby = Request::input('orderby') == 'asc'? 'asc': 'desc';
        switch($order)
        {
            case 'name':
            case 'status':
            case 'created_at':
                break;
            
            default:
                $order = 'created_at';
                $orderby = 'desc';
                break;
        }
                
        $categories = StoreCategory::where('customer_id', $this->current_customer->id)
            ->orderBy($order, $orderby)
            ->paginate(15);
    
        $this->page = 'category';
        return $this->view('site.store.category.list', compact('categories'));            
    }
    
    /**
    * get category form - new/edit
    * 
    */
    public function categoryForm()
    {
        $category = null;
        
        if(Input::has('category_id'))
        {
            $category = StoreCategory::find(Input::get('category_id'));
        }
        
        return $this->view('site.store.category.form', compact('category'));            
    }
    
    public function categorySave()
    {
        $category = null;
        $url = '';
                
        if(Input::has('category_id'))
        {
            $category = StoreCategory::find(Input::get('category_id'));
            
            Template::set_message('Category is updated.', 'success');
            $url = route('site.store.category.list', ['order' => 'created_at', 'orderby' => 'desc']);
        }
        else
        {
            $category = new StoreCategory;
            
            Template::set_message('New category is created.', 'success');
            $url = my_route('site.store.category.list');     
        }
        
        $category->customer_id = $this->current_customer->id;
        $category->name = Input::get('category_name');
        $category->save();
        
        return redirect($url);
    }
    
    /**
    * delete category item
    * 
    */
    public function categoryDelete($id)
    {
        $status = StoreCategory::destroy($id);
        
        if($status) Template::set_message('Category is removed.', 'success');
        else Template::set_message(trans('msg.something_wrong'), 'danger');
        
        return redirect(my_route('site.store.category.list'));
    }
    
    /**
    * update category status - active/deactive
    * 
    */
    public function categoryUpdateStatus()
    {
        $result = array('status' => true, 'error' => '', 'msg' => '');
        
        $categoryId = Input::get('category_id');
        $category = StoreCategory::find($categoryId);
        
        if($category->status == STATUS_ACTIVE)
        {
            $category->status = STATUS_INACTIVE;
            
            $result['msg'] = 'Category is deactivated.';
        }
        else
        {
            $category->status = STATUS_ACTIVE;
            
            $result['msg'] = 'Category is activated.';
        }   
        $category->save();
             
        $result['category_status'] = $category->status;
        echo json_encode($result);
        exit;
    }
    
    ////////////////////////////
    //order
    public function orders()
    {
        $site = $this->cart->getSite();
        
        //get orders
        $this->saveSorting('site.store.category.list');
        
        $order = Request::input('order');
        $orderby = Request::input('orderby') == 'asc'? 'asc': 'desc';
        switch($order)
        {
            case 'name':
                $order = 'first_name';
                break;
            case 'email':
                $order = 'payer_email';
                break;
            case 'phone':
                $order = 'phone_no';
                break;
            case 'price':
                $order = 'grand_total';
                break;
            case 'status':
            case 'created_at':
                break;
            
            default:
                $order = 'created_at';
                $orderby = 'desc';
                break;
        }
         
        $orders = StoreOrder::where('store_id', $site->store_id)
            ->orderBy($order, $orderby)
            ->paginate(15);    
        
        $this->page = 'order';
        return $this->view('site.store.order.list', compact('orders'));         
    }
    
    ////////////////////////////
    //product
    
    /**
    * display all product lists
    * 
    */
    public function products()
    {
        $products = StoreProduct::where('customer_id', $this->current_customer->id)
            ->orderBy('name', 'asc')
            ->get();
        
        $this->page = 'product';    
        return $this->view('site.store.product.list', compact('products'));     
    }
    
    /**
    * get product form - new/edit
    * 
    */
    public function productForm()
    {
        $categories = StoreCategory::where('customer_id', $this->current_customer->id)
            ->orderBy('name', 'asc')
            ->lists('name', 'id')
            ->toArray();
        $categories = array('' => 'Select type') + $categories;
        
        $campaigns = Campaign::where('customer_id', $this->current_customer->id)
            ->orderBy('name', 'asc')
            ->lists('name', 'id')
            ->toArray();
        $campaigns = array('' => 'Select campaign') + $campaigns;
        
        $shippings = StoreShipping::where('customer_id', $this->current_customer->id)
            ->orderBy('name', 'asc')
            ->lists('name', 'id')
            ->toArray();
        $shippings = array('' => 'Select shipping') + $shippings;
        
        $product = StoreProduct::findOrNew(Input::get('product_id'));
        $productImages = null;
        if($product->id)
        {
            $productImages = StoreProductImage::where('product_id', Input::get('product_id'))->get();
        }
        else
        {
            $productImages = StoreProductImage::where('key', Input::get('product_key'))->get();
        }
        
        
        return $this->view('site.store.product.form', compact('categories', 'campaigns', 'shippings', 'product', 'productImages'));
    }
    
    /**
    * save product info to db
    * 
    */
    public function productSave()
    {
        //save product
        $product = StoreProduct::findOrNew(Input::get('product_id'));
        $product->customer_id   = $this->current_customer->id;
        $product->name          = Input::get('product_name');
        $product->description   = Input::get('product_description');
        $product->price         = Input::get('product_price');
        $product->rebill_price  = Input::get('rebill_price');
        $product->campaign_id   = Input::get('product_campaign');
        $product->shipping_id   = Input::get('shipping');
        $product->category_id   = Input::get('product_category');
        $product->is_trial      = Input::get('is_trial');
        $product->trial_length  = Input::get('trial_length');
        $product->limelight_payment_name  = Input::get('payment_name');
        $product->save();
        
        if(!Input::get('product_id'))
        {
            StoreProductImage::where('key', Input::get('product_key'))
                ->update(['product_id' => $product->id]);
            
            Template::set_message('New product is created.', 'success');
            $url = route('site.store.product.list', ['order' => 'created_at', 'orderby' => 'desc']);
        }
        else
        {
            Template::set_message('Product is created.', 'success');
            $url = my_route('site.store.product.list');
        }
        
        return redirect($url);
    }
    
    /**
    * delete product item
    * 
    */
    public function productDelete($id)
    {
        $status = StoreProduct::destroy($id);
             
        if($status) Template::set_message('Product is removed.', 'success');
        else Template::set_message(trans('msg.something_wrong'), 'danger');
        
        return redirect(my_route('site.store.product.list'));
    }
    
    /**
    * get product image list
    * 
    */
    public function productImages()
    {
        $productImages = null;
        if(Input::has('product_id'))
        {
            $productImages = StoreProductImage::where('product_id', Input::get('product_id'))->get();
        }        
        else
        {
            $productImages = StoreProductImage::where('key', Input::get('product_key'))->get();
        }
        
        return $this->view('site.store.product.images', compact('productImages'));
    }
    
    /**
    * upload product image
    * 
    */
    public function productImageUpload() 
    {
        $result = ['status' => true, 'errors' => array()];
        $errors = array();
        
        //get site from session
        $site = $this->cart->getSite();
        
        $productId = Input::get('product_id');
        
        if(isset($_FILES) && count($_FILES['photos']['name']))
        {
            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            
            foreach ($_FILES['photos']['name'] as $name => $value)
            {
                $uploadDir = UPLOADS_BASE . '/site/product/' . strtolower(str_random(1) . '/' . str_random(1) . '/');
                
                $fileName = strtolower($value);
                $fileSize = filesize($_FILES['photos']['tmp_name'][$name]);
                $fileParts = pathinfo($fileName);
                
                if (in_array($fileParts['extension'], $fileTypes))
                {
                    if ($fileSize < 10000 * 1024) //10M
                    {
                        mkpath($uploadDir);
                        
                        $newName = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newName)) 
                        {
                            $count = StoreProductImage::where('product_id', $productId)->count();
                            
                            $productImage = new StoreProductImage;
                            $productImage->product_id = $productId;
                            $productImage->image_name = $newName;
                            if($count == 0) $productImage->main = 1;
                            if(!$productId) $productImage->key = Input::get('product_key');
                            
                            $productImage->save();
                        }
                        else
                        {
                            $errors[] = "[$value] - Exceeded the size limit! so moving unsuccessful!";
                        }  
                    }
                    else
                    {
                        $errors[] = "[$value] - Exceeded the size limit!";
                    }
                }
                else
                {
                    $errors[] = 'Unknown extension!';
                }
            }
        }
        
        $result['errors'] = $errors;
        echo json_encode($result);
        exit; 
    }
    
    /**
    * remove product image
    * 
    */
    public function productImageDelete()
    {
        $imageId = Input::get('image_id');
        
        StoreProductImage::destroy($imageId);
        
        $productImages = null;
        if(Input::has('product_id'))
        {
            $productImages = StoreProductImage::where('product_id', Input::get('product_id'))->get();
        }        
        else
        {
            $productImages = StoreProductImage::where('key', Input::get('product_key'))->get();
        }
        
        return $this->view('site.store.product.images', compact('productImages'));
    }
    
    /**
    * set product image as main image
    * 
    */
    public function productImageMakeMain()
    {
        $result = ['status' => true, 'error' => ''];
        
        $field = 'product_id';
        $value = Input::get('product_id');
        
        if(!Input::has('product_id'))
        {
            $field = 'key';
            $value = Input::get('product_key');    
        }    
        
        StoreProductImage::where($field, $value)->update(['main' => 0]);
        StoreProductImage::where('id', Input::get('image_id'))->update(['main' => 1]);
        
        echo json_encode($result);
        exit;
    }
    
    ////////////////////////////
    //setting
    
    /**
    * get store general settings
    * 
    */
    public function settingsGeneral()
    {
        $site = $this->cart->getSite();
        
        $store = $site->store();
        
        $this->page = 'general';
        return $this->view('site.store.setting.general', compact('store'));            
    }
    
    /**
    * get store checkout settings
    *  
    */
    public function settingsCheckout()
    {
        $site = $this->cart->getSite();
        
        $store = $site->store();
        
        $this->page = 'checkout';
        return $this->view('site.store.setting.checkout', compact('store'));            
    }
    
    /**
    * get store tax settings
    *  
    */
    public function settingsTax()
    {
        $site = $this->cart->getSite();
        
        $store = $site->store();
        
        $this->page = 'tax';
        return $this->view('site.store.setting.tax', compact('store'));            
    }
    
    /**
    * get store shipping settings
    *  
    */
    public function settingsShipping()
    {
        $site = $this->cart->getSite();
        
        $store = $site->store();
        
        $this->page = 'shipping';
        return $this->view('site.store.setting.shipping', compact('store'));            
    }
    
    /**
    * get store email settings
    *  
    */
    public function settingsEmail()
    {
        $site = $this->cart->getSite();
        
        $store = $site->store();
        
        $this->page = 'email';
        return $this->view('site.store.setting.email', compact('store'));            
    }
}