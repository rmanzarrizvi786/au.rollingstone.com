<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap flex-reverse">
                <!-- <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="https://via.placeholder.com/600x800/000/000?text=.">
                    </div>
                </div> -->
                <div class="content-text">
                    <h2 class="title font-heading text-yellow">
                        THE
                        <span class="text-decade"><?php echo $decade; ?>'s</span>
                    </h2>
                    <div class="divider"></div>
                    <div class="text-center intro text-white">
                        <p>The digital revolution has taken over as all the world’s entire music library becomes available inside a smartphone. New talent breaks through from all corners of the globe –– with artists proudly wearing their independence on their sleeves.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-yellow text-black">
                            <p>&quot;It doesn't matter if you love him, or capital H-I-M / Just put your paws up.&quot; –– <em>Lady Gaga, 'Born This Way', 2011</em></p>
                            <div class="popover-plus pulse bg-yellow text-red" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 500; ?>="opacity: 1;">
                                <span data-bs-toggle="popover2" data-bs-placement="<?php echo $i % 2 == 0 ? 'left' : 'right'; ?>" title="<?php echo $decade; ?>" data-bs-content="">
                                    ★
                                </span>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</section>