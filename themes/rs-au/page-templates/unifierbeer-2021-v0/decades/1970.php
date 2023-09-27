<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap flex-reverse">
                <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021/images/punk-lady.png">
                    </div>
                </div>
                <div class="content-text">
                    <h2 class="title font-heading text-yellow">
                        THE
                        <span class="text-decade"><?php echo substr($decade, -2); ?>'s</span>
                    </h2>
                    <div class="divider"></div>
                    <div class="text-center intro text-black">
                        <p>As the hippies fade from view, a new sound emerges: the white-hot, unhinged sound of punk. Teeming with raw aggression and break-neck guitar riffs, punk becomes the definitive anti-establishment soundtrack pioneered by youth in revolt. Meanwhile, the civil rights movement continues to push forward as a diverse coalition of change-makers demand equality for all.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-yellow text-black">
                            <p>&quot;When the law break in / How you gonna go? / Shot down on the pavement /Or waiting on death row.&quot; — <em>The Clash, 'The Guns of Brixton', 1979</em></p>
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