<section class="page decade bg-white" id="protest-music" style="z-index: 200; min-height: 0; padding-bottom: 4rem;">
  <div class="container">
    <div class="inner">
      <div class="content-wrap">
        <div class="content-text text-black">
          <div class="text-center" style="margin-top: 3rem;">
            <h2 class="title font-heading text-black">
              Protest Music Through the Ages
            </h2>
            <p class="font-body" style="margin-top: 1rem;">Protest music is as relevant today as it was in the '60s. We asked some of our favourite artists to share the tracks that mean the most to them to celebrate the release of The Unifier.</p>
          </div>
        </div>
      </div>
      <div id="carouselProtestVids" class="carousel slide" data-bs-interval="false">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Video 1">★</button>
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="1" aria-label="Video 2">★</button>
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="2" aria-label="Video 3">★</button>
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="3" aria-label="Video 4">★</button>
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="4" aria-label="Video 5">★</button>
          <button type="button" data-bs-target="#carouselProtestVids" data-bs-slide-to="5" aria-label="Video 6">★</button>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProtestVids" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselProtestVids" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div id="protest-music-vid-1" class="protest-music-vid" data-vid="x81in71"></div>
          </div>
          <div class="carousel-item">
            <div id="protest-music-vid-2" class="protest-music-vid" data-vid="x81in72"></div>
          </div>
          <div class="carousel-item">
            <div id="protest-music-vid-3" class="protest-music-vid" data-vid="x8266og"></div>
          </div>
          <div class="carousel-item">
            <div id="protest-music-vid-4" class="protest-music-vid" data-vid="x8266oh"></div>
          </div>
          <div class="carousel-item">
            <div id="protest-music-vid-5" class="protest-music-vid" data-vid="x8266oi"></div>
          </div>
          <div class="carousel-item">
            <div id="protest-music-vid-6" class="protest-music-vid" data-vid="x82vrqo"></div>
          </div>
        </div>
      </div>

      <!-- <div class="d-flex flex-column flex-md-row">
        <div id="protest-music-vid-1" class="protest-music-vid p-2" data-vid="x81in71"></div>
        <div id="protest-music-vid-2" class="protest-music-vid p-2" data-vid="x81in72"></div>
      </div> -->

      <style>
        .carousel-inner {
          width: calc(100% - 70px);
          margin: auto;
        }

        .carousel-control-next,
        .carousel-control-prev {
          width: 30px;
          height: 30px;
          top: 50%;
          transform: translateY(-50%);
          bottom: initial;
          border-radius: 50%;
          padding: .5rem;
          background: #000 !important;
          opacity: 1;
        }

        .carousel-control-prev {
          background: linear-gradient(270deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 1) 100%);
        }

        .carousel-control-next {
          background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 1) 100%);
        }

        .carousel-inner,
        .protest-music-vid {
          border-radius: 1rem;
        }

        @media(min-width: 48rem) {
          .protest-music-vid {
            height: 450px;
            /* height: 225px; */
          }

          .carousel-inner {
            width: calc(100% - 140px);
          }

          .carousel-control-next,
          .carousel-control-prev {
            width: 60px;
            height: 60px;
          }
        }

        @media(min-width: 60rem) {
          .protest-music-vid {
            height: 625px;
            /* height: 350px; */
          }

          .carousel-inner {
            width: calc(100% - 140px);
          }

          .carousel-control-next,
          .carousel-control-prev {
            width: 60px;
            height: 60px;
          }
        }

        .carousel-indicators {
          bottom: -3rem;
        }

        .carousel-indicators [data-bs-target] {
          background-color: transparent;
          color: #000;
          text-indent: 0;
          width: 30px;
          height: 30px;
          border: none;
          opacity: .25;
        }

        .carousel-indicators .active {
          color: #d20208;
          opacity: 1;
        }
      </style>

      <script>
        jQuery(document).ready(function($) {
          protestMusicPlayers = new Array(5);

          $('.protest-music-vid').each(function(i, elem) {
            protestMusicPlayers[i] = DM.player(document.getElementById($(elem).attr('id')), {
              video: $(elem).data('vid'),
              width: "100%",
              // height: "600",
              params: {
                autoplay: false,
                "queue-enable": false,
                "queue-autoplay-next": false,
                loop: true
              }
            });
          });

          /* protestMusicPlayers[0] = DM.player(document.getElementById('protest-music-vid-1'), {
            video: 'x81in71',
            width: "100%",
            height: "600",
            params: {
              autoplay: false,
              "queue-enable": false,
              "queue-autoplay-next": false,
              loop: true
            }
          });
 */
          var carouselProtestVids = document.getElementById('carouselProtestVids')
          carouselProtestVids.addEventListener('slid.bs.carousel', function(e) {
            protestMusicPlayers[e.from].pause();
            protestMusicPlayers[e.to].play();
          })
          /* $('.protest-music-vid').each(function(i, elem) {
            var protestMusicPlayer = DM.player(document.getElementById($(elem).attr('id')), {
              video: $(elem).data('vid'),
              width: "100%",
              height: "600",
              params: {
                autoplay: false,
                "queue-enable": false,
                "queue-autoplay-next": false,
                loop: true
              }
            });
          }); */
        });
        /*  */
      </script>

    </div>
  </div>
</section>