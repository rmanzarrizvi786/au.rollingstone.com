<form action="#" method="post" class="form-bonds" id="form-bonds-2022">
    <div class="d-flex justify-content-start">
        <div class="col-12 col-md-2 px-0 px-md-1">
            <div class="d-flex flex-column align-items-stretch justify-content-start mt-2">
                <div>QTY</div>
                <div class="flex-fill">
                    <select id="quantity" name="quantity" class="form-control">
                        <?php foreach (range(1, 10) as $quantity) : ?>
                            <option value="<?php echo $quantity; ?>"><?php echo $quantity; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex mt-2">
        <div class="col-12 col-md-6 px-0 px-md-1">
            <div class="d-flex flex-column align-items-stretch justify-content-start mt-2">
                <div>YOUR NAME</div>
                <div class="flex-fill">
                    <input type="text" id="buyer_full_name" name="buyer_full_name" class="form-control mt-1" placeholder="FULL NAME *">
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 px-0 px-md-1">
            <div class="d-flex flex-column align-items-stretch justify-content-start mt-2">
                <div>EMAIL ADDRESS</div>
                <div class="flex-fill">
                    <input type="text" id="email" name="email" class="form-control mt-1" placeholder="EMAIL *">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex mt-2">
        <div class="col-12 px-0 px-md-1">
            <div class="d-flex flex-column align-items-stretch justify-content-start mt-2">
                <div>ADDRESS</div>
                <div class="flex-fill">
                    <input type="text" id="address_1" name="address_1" class="form-control mt-1" placeholder="ADDRESS LINE 1 *">
                    <div class="d-flex">
                        <div class="col-12 col-md-6 pr-0 pr-md-1">
                            <input type="text" id="address_2" name="address_2" class="form-control mt-1" placeholder="ADDRESS LINE 2">
                        </div>
                        <div class="col-12 col-md-6 pl-0 pl-md-1">
                            <input type="text" id="city" name="city" class="form-control mt-1" placeholder="CITY *">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-12 col-md-4 pr-0 pr-md-1">
                            <input type="text" id="state" name="state" class="form-control mt-1" placeholder="STATE *">
                        </div>
                        <div class="col-12 col-md-4 px-0 px-md-1">
                            <input type="text" id="postcode" name="postcode" class="form-control mt-1" placeholder="POSTCODE (ZIP) *">
                        </div>
                        <div class="col-12 col-md-4 pl-0 pl-md-1">
                            <select class="form-control mt-1" name="country" id="country" required>
                                <option value="" disabled selected>COUNTRY *</option>
                                <?php foreach (TBM\Bonds\Bonds2022::getCountries() as $country_code => $country) : ?>
                                    <option value="<?php echo $country; ?>" <?php echo isset($_SESSION['sub_country']) && $country == $_SESSION['sub_country'] ? ' selected' : '';
                                                                            echo '' == $country_code ? ' disabled' : ''; ?>><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 px-0 px-md-1">
        <label class="d-flex justify-content-start">
            <div class="pr-md-1 billing_tick">
                <input type="checkbox" name="purchase_as_gift" id="purchase_as_gift" value="1" <?php echo  isset($_GET['gift']) ? 'checked' : '' ?>>
            </div>
            <div>Purchase as a gift</div>
        </label>
    </div>

    <div class="d-flex mt-2" id="shipping_address_wrap" style="<?php echo isset($_GET['gift']) ? '' : ' display: none;' ?>">
        <div class="col-12 px-0 px-md-1">
            <div class="col-12 col-md-6 px-0 px-md-1">
                <div class="d-flex flex-column align-items-stretch justify-content-start mt-2">
                    <div>RECEIVER'S NAME</div>
                    <div class="flex-fill">
                        <input type="text" id="shipping_full_name" name="shipping_full_name" class="form-control mt-1" placeholder="RECEIVER'S FULL NAME *">
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column align-items-stretch justify-content-start mt-2 px-0 px-md-1">
                <div>SHIPPING ADDRESS</div>
                <div class="flex-fill">
                    <input type="text" id="shipping_address_1" name="shipping_address_1" class="form-control mt-1" placeholder="SHIPPING ADDRESS LINE 1 *">
                    <div class="d-flex">
                        <div class="col-12 col-md-6 pr-0 pr-md-1">
                            <input type="text" id="shipping_address_2" name="shipping_address_2" class="form-control mt-1" placeholder="SHIPPING ADDRESS LINE 2">
                        </div>
                        <div class="col-12 col-md-6 pl-0 pl-md-1">
                            <input type="text" id="shipping_city" name="shipping_city" class="form-control mt-1" placeholder="SHIPPING CITY *">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-12 col-md-4 pr-0 pr-md-1">
                            <input type="text" id="shipping_state" name="shipping_state" class="form-control mt-1" placeholder="SHIPPING STATE *">
                        </div>
                        <div class="col-12 col-md-4 px-0 px-md-1">
                            <input type="text" id="shipping_postcode" name="shipping_postcode" class="form-control mt-1" placeholder="SHIPPING POSTCODE (ZIP) *">
                        </div>
                        <div class="col-12 col-md-4 pl-0 pl-md-1">
                            <select class="form-control mt-1" name="shipping_country" id="shipping_country" required>
                                <option value="" disabled selected>SHIPPING COUNTRY *</option>
                                <?php foreach (TBM\Bonds\Bonds2022::getCountries() as $country_code => $country) : ?>
                                    <option value="<?php echo $country; ?>" <?php echo isset($_SESSION['sub_country']) && $country == $_SESSION['sub_country'] ? ' selected' : '';
                                                                            echo '' == $country_code ? ' disabled' : ''; ?>><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if (TBM\Bonds\Bonds2022::$free_underwear) : ?>
        <div class="mt-4 flex-fill px-0 px-md-1" style="border-top: 1px solid #707070;"></div>
        <div>
            <div class="mt-4 px-0 px-md-1">
                <h2 class="text-center my-2">Free Gift</h2>
                <p class="p2">
                    Congratulations! You've recieved a BONDS TOTAL PACKAGE™ UNDERWEAR set.
                </p>
                <div>Select Size</div>
                <div>
                    <select id="underwear_size" name="underwear_size" class="form-control" style="width: 120px;">
                        <option value="">-</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="x-large">X-Large</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-4 flex-fill px-0 px-md-1" style="border-top: 1px solid #707070;"></div>
    <?php endif; ?>


    <div class="d-flex">
        <div class="mt-4 flex-fill px-0 px-md-1">
            <h2 class="text-center my-2">ORDER SUMMARY</h2>
            <div class="d-flex justify-content-between mt-1 pb-1" style="border-bottom: 1px solid #ccc">
                <div>Calendar</div>
                <div><span class="base-price">$29.90</span></div>
            </div>
            <div class="d-flex justify-content-between mt-1 pb-1" style="border-bottom: 1px solid #ccc">
                <div>Shipping</div>
                <div class="shipping-price">Please select country</div>
            </div>
            <div class="d-flex justify-content-between mt-1 pb-1" style="border-bottom: 1px solid #ccc">
                <div><strong>Total</strong></div>
                <div><strong class="amount-to-pay">Please select country</strong></div>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <div class="col-12 mt-4">
            <div style="width: 100%; margin: auto;">
                <div id="card-element" class="card-element"></div>

                <div id="js-success" class="success"></div>
                <div id="js-errors" class="error" role="alert"></div>
                <button id="submit-bonds-2022" type="button" class="mt-1">
                    <div id="spinner" class="hidden"></div>
                    <span id="button-text" style="height: auto;">SUBMIT</span>
                </button>
            </div>
        </div>
    </div>

</form>