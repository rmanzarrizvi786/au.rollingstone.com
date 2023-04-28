<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap">
                <!-- <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="https://via.placeholder.com/600x800/000/000?text=.">
                    </div>
                </div> -->
                <div class="content-text">
                    <h2 class="title font-heading text-black">
                        THE
                        <span class="text-decade"><?php echo $decade; ?>'s</span>
                    </h2>
                    <div class="divider bg-red"></div>
                    <div class="text-center intro text-black">
                        <p>Indie rock and emo dominate the charts, with skinny-jeaned rock revivalists turning heads across the US, Europe and Australia. Millennials become teenagers to the sounds of Tegan and Sara, The Strokes, Arctic Monkeys, and The Yeah Yeah Yeahs. Armed with Tumblr, a Motorola flip-phone and the birth of social media –– young people become attuned at using the internet to discover new music, new communities and new subcultures.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-black text-yellow">
                            <p>&quot;Don't want to be an American idiot / One nation controlled by the media / Information age of hysteria / It's calling out to idiot America.&quot; - <em>Green Day, 'American Idiot', 2004</em></p>
                            <div class="popover-plus pulse" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 500; ?>="opacity: 1;">
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