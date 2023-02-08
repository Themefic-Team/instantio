<?php 
namespace INS\Controller;

class App {
    public function __construct() { 
        add_action( 'wp_footer', array($this, 'instantio_layout_three'), 10 );
    }
    public function instantio_layout_three(){
        ob_start();
        ?>
        <div class="ins-checkout-overlay"></div>
        <div class="ins-checkout-layout-3 slide">
         
            <div class="ins-click-to-show ins-toggle-btn">
                <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xmlns:v="https://vecta.io/nano"><path d="M490.299 185.717H384.08L324.496 49.284c-3.315-7.591-12.157-11.06-19.749-7.743s-11.059 12.158-7.743 19.75l54.34 124.427H160.656l54.34-124.427c3.315-7.592-.151-16.434-7.743-19.75a15 15 0 0 0-19.749 7.743L127.92 185.717H21.701c-13.895 0-24.207 12.579-21.167 25.82l55.935 243.63c2.221 9.674 11.015 16.55 21.167 16.55h356.728c10.152 0 18.946-6.876 21.167-16.55l55.935-243.63c3.04-13.24-7.273-25.82-21.167-25.82zm-359.557 46.004c-2.004 0-4.041-.404-5.996-1.258-7.592-3.315-11.059-12.157-7.743-19.75l11.268-25.802h32.736l-16.512 37.808c-2.461 5.639-7.971 9.002-13.753 9.002zM181 391.717c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm26.253-161.254a14.94 14.94 0 0 1-5.995 1.258c-5.782 0-11.292-3.362-13.754-9.001l-16.512-37.808h32.736l11.268 25.802a15 15 0 0 1-7.743 19.749z"></path></svg>
    
                <span class="ins-items-count"><span class="ins_cart_total">10</span></span>
            </div>
            <span class="ins-checkout-close"><svg width="18px" height="18px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M764.288 214.592 512 466.88 259.712 214.592a31.936 31.936 0 0 0-45.12 45.12L466.752 512 214.528 764.224a31.936 31.936 0 1 0 45.12 45.184L512 557.184l252.288 252.288a31.936 31.936 0 0 0 45.12-45.12L557.12 512.064l252.288-252.352a31.936 31.936 0 1 0-45.12-45.184z"/></svg></span> 
            <h4 class="ins-label">Your Cart</h4>
            <div class="ins-steps">
                <button class="ins-step-btn active" data-step="ins-step-1">Order Summary</button>
                <button class="ins-step-btn" data-step="ins-step-2">Shipping</button>
                <button class="ins-step-btn" data-step="ins-step-3">Payment</button> 
            </div>
            <div class="ins-content">
                <div class="ins-single-step ins-step-1 active"> 
                    <h4 class="ins-label">step one</h4>
                    <div class="ins-cart-item-wrap">
                        <div class="ins-single-cart-item">
                            <div class="ins-cart-item-img">
                                <img src="https://images.pexels.com/photos/1630640/pexels-photo-1630640.jpeg" alt="">
                            </div>
                            <div class="ins-cart-item-content">
                                <div class="ins-cart-item-title">
                                    <a href="#" class="ins-cart-title">Product Title</a>
                                    <span class="ins-cart-item-price">$ 100</span> 
                                </div>
                                <div class="ins-cart-item-quantity">
                                    <span class="ins-cart-plus">+</span>
                                    <span class="ins-cart-qty"> 1</span>
                                    <span class="ins-cart-minus">-</span>
                                </div> 
                                <span class="ins-cart-item-remove">x</span>
                            </div>
    
                        </div>
                        <div class="ins-single-cart-item">
                            <div class="ins-cart-item-img">
                                <img src="https://images.pexels.com/photos/1630640/pexels-photo-1630640.jpeg" alt="">
                            </div>
                            <div class="ins-cart-item-content">
                                <div class="ins-cart-item-title">
                                    <a href="#" class="ins-cart-title">Product Title</a>
                                    <span class="ins-cart-item-price">$ 100</span> 
                                </div>
                                <div class="ins-cart-item-quantity">
                                    <span class="ins-cart-plus">+</span>
                                    <span class="ins-cart-qty"> 1</span>
                                    <span class="ins-cart-minus">-</span>
                                </div> 
                                <span class="ins-cart-item-remove">x</span>
                            </div>
    
                        </div>
                        <div class="ins-single-cart-item">
                            <div class="ins-cart-item-img">
                                <img src="https://images.pexels.com/photos/1630640/pexels-photo-1630640.jpeg" alt="">
                            </div>
                            <div class="ins-cart-item-content">
                                <div class="ins-cart-item-title">
                                    <a href="#" class="ins-cart-title">Product Title</a>
                                    <span class="ins-cart-item-price">$ 100</span> 
                                </div>
                                <div class="ins-cart-item-quantity">
                                    <span class="ins-cart-plus">+</span>
                                    <span class="ins-cart-qty"> 1</span>
                                    <span class="ins-cart-minus">-</span>
                                </div> 
                                <span class="ins-cart-item-remove">x</span>
                            </div>
    
                        </div>
                        <button class="ins-empty-cart">Empty Cart</button>
                    </div>
                    <div class="ins-cart-totals">
                        <ul>
                            <li>Subtotal <span class="price">$187</span></li>
                            <li>Delivery <span class="price">$8</span></li>
                            <li>Taxes <span class="price">$13</span></li>
                            <li><strong>Total</strong> <span class="price"><strong>$208</strong></span></li>
                        </ul>
                    </div>
                    <div class="ins-cart-btns">
                        <a href="#" class="view-cart active">View Cart</a>
                        <a href="#" class="checkout">Checkout Now</a>
                    </div>
                </div>
                <div class="ins-single-step ins-step-2">
                    <span class="ins-label">Your Cart 2</span>
                    <div class="ins-cart-item-wrap">
                        <div class="ins-single-cart-item">
                            <div class="ins-cart-item-img">
                                <img src="https://images.pexels.com/photos/1630640/pexels-photo-1630640.jpeg" alt="">
                            </div>
                            <div class="ins-cart-item-content">
                                <div class="ins-cart-item-title">
                                    <h4>Product Title</h3>
                                    <span class="ins-cart-item-quantity">Quantity: 1</span>
                                </div>
                                <div class="ins-cart-item-price">
                                    <span>$ 100</span>
                                </div> 
                                <span class="ins-cart-item-remove">x</span>
                            </div>
    
                        </div>
                        <button class="ins-empty-cart">Empty Cart</button>
                    </div>
                    <div class="ins-cart-totals">
                        <ul>
                            <li>Subtotal <span class="price">$187</span></li>
                            <li>Delivery <span class="price">$8</span></li>
                            <li>Taxes <span class="price">$13</span></li>
                            <li>Total <span class="price">$208</span></li>
                        </ul>
                    </div>
                    <div class="ins-cart-btns">
                        <a href="#" class="view-cart">View Cart</a>
                        <a href="#" class="checkout">Checkout Now</a>
                    </div>
                </div>
                <div class="ins-single-step ins-step-3">
                    <span class="ins-label">Your Cart 3</span>
                    <div class="ins-cart-item-wrap">
                        <div class="ins-single-cart-item">
                            <div class="ins-cart-item-img">
                                <img src="https://images.pexels.com/photos/1630640/pexels-photo-1630640.jpeg" alt="">
                            </div>
                            <div class="ins-cart-item-content">
                                <div class="ins-cart-item-title">
                                    <h3>Product Title</h3>
                                    <span class="ins-cart-item-quantity">Quantity: 1</span>
                                </div>
                                <div class="ins-cart-item-price">
                                    <span>$ 100</span>
                                </div> 
                                <span class="ins-cart-item-remove">x</span>
                            </div>
    
                        </div>
                        <button class="ins-empty-cart">Empty Cart</button>
                    </div>
                    <div class="ins-cart-totals">
                        <ul>
                            <li>Subtotal <span class="price">$187</span></li>
                            <li>Delivery <span class="price">$8</span></li>
                            <li>Taxes <span class="price">$13</span></li>
                            <li>Total <span class="price">$208</span></li>
                        </ul>
                    </div>
                    <div class="ins-cart-btns">
                        <a href="#" class="view-cart active">View Cart</a>
                        <a href="#" class="checkout">Checkout Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    }
 
     
}


?>