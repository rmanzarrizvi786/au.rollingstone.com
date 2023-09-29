<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap">
                <!-- <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="https://via.placeholder.com/600x800/f7f0e6/f7f0e6?text=.">
                    </div>
                </div> -->
                <div class="content-text">
                    <h2 class="title font-heading text-white">
                        THE
                        <span class="text-decade"><?php echo $decade; ?>'s</span>
                    </h2>
                    <div class="divider bg-white"></div>
                    <div class="text-center intro w-100">
                        <p>As we enter the 2020s, we see the death of the genre. Music is no longer boxed into a time, place or category but rather treated as something fluid. Black Lives Matter comes to the forefront of culture, as a global movement takes to the streets to have their voices heard. As they march, we hear diverse sounds––sounds as diverse as those demanding change. The work isn’t done yet, but progress is being made as the next generation of creatives arise.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-yellow text-black">
                            <div>
                                <p>&quot;When we gonna start the conversation? / When we gonna start the celebration? / When we gonna end the exploitation? / When we gonna say the word "invasion"?s&quot; - <em>Midnight Oil, Jessica Mauboy and Tasman Keith, 'First Nation', 2020</em></p>
                                <div class="popover-plus pulse" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 500; ?>="opacity: 1;">
                                    <span data-bs-toggle="popover2" data-bs-placement="<?php echo $i % 2 == 0 ? 'left' : 'right'; ?>" title="<?php echo $decade; ?>">
                                        ★
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>