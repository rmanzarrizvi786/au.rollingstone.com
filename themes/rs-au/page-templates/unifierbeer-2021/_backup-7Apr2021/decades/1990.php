<section id="decade<?php echo $decade; ?>" class="page decade decade<?php echo $decade; ?>" data-nav="<?php echo $decade; ?>" data-<?php echo ($i * $scroll_number) - 200; ?>="top: 100%;" data-<?php echo $i * $scroll_number; ?>="top: 0%;">
    <div class="container">
        <div class="inner">
            <div class="content-wrap flex-reverse">
                <div class="content-img" data-<?php echo ($i * $scroll_number - 200); ?>="margin-top: 450px;" data-<?php echo $i * $scroll_number; ?>="margin-top: 70px;">
                    <div style="position: relative;">
                        <img src="<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021/images/hiphop-guy-A-bronx-3.jpg">
                    </div>
                </div>
                <div class="content-text">
                    <h2 class="title font-heading text-green">
                        THE
                        <span class="text-decade"><?php echo substr($decade, -2); ?>'s</span>
                    </h2>
                    <div class="divider bg-white"></div>
                    <div class="text-center intro text-white">
                        <p>The birth of hip hop sets the West Coast and East Coast alight––becoming the poetry of the streets and a mouthpiece for oppressed youth. A movement that centres the Black experience takes hold and spreads across the globe as rappers including Tupac, Lil Kim, NWA, Lauryn Hill and Public Enemy become household names. Together, they bring issues of Black liberation, police brutality, the prison industrial complex and more to the national conversation––preaching unity over hate and equality over division.</p>
                    </div>

                    <div data-<?php echo ($i * $scroll_number) + 100; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 1;">
                        <div class="highlight-box bg-yellow text-black">
                            <p>&quot;The question I wonder is after death, after my last breath/ When will I finally get to rest through this oppression?/ They punish the people that's asking questions/ And those that possess steal from the ones without possessions.&quot; –– <em>Tupac, 'Me Against the World', 1995</em></p>
                            <div class="popover-plus pulse bg-green" data-<?php echo ($i * $scroll_number) + 300; ?>="opacity: 0;" data-<?php echo ($i * $scroll_number) + 500; ?>="opacity: 1;">
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