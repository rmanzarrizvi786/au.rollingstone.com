<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap">
                <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021/images/Peace-TV-V2.jpg">
                    </div>
                </div>
                <div class="content-text">
                    <h2 class="title font-heading text-black">
                        THE
                        <span class="text-decade"><?php echo substr($decade, -2); ?>'s</span>
                    </h2>
                    <div class="divider"></div>
                    <div class="text-center intro">
                        <p>Welcome to the swinging 60s. The sounds of revolution are in the air. Electric guitars, mop tops, free love, and a burgeoning fascination with psychedelics are all infiltrating society. Norms are upheaved as the rigidity of gender roles begin to be questioned. It all culminates at Woodstock, as bare-footed young people come together under the banner of peace and social change in a counterculture event that defined a generation.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-black text-white">
                            <p>&quot;Your sons and your daughters / Are beyond your command / Your old road is rapidly ageing.&quot; — <em>Bob Dylan, 'The Times They Are a-Changin', 1964</em></p>
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
</section>