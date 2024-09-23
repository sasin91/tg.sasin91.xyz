<canvas id="article--trongate_canvas"></canvas>

<article id="article--trongate" class="container">
    <img alt="Trongate: The framework they don't want you to know about"
         src="/blog_module/images/trongate_logo_trans_bg.png" class="trongate-logo">
    <h1 class="text-fancy"><?= $t('trongate.intro') ?></h1>
    <section id="trongate_mhavc">
        <h2 id="tagline"><?= $t('trongate.tagline') ?></h2>
        <figure>
            <blockquote>
                <?= $t('trongate.mhavc') ?>
            </blockquote>
            <img src="/blog_module/images/trongate_lvim_mangos-account.png"
                 id="trongate-mhavc-img"
                 alt="Trongate modular structure"
            >
            <figcaption class="mt-6 flex gap-x-4">
                <a href="https://trongate.io/docs/basic-concepts/truly-modular-architecture.html">
                    <?= $t('read_more') ?>
                </a>
            </figcaption>
        </figure>
        <p>
            <?= $t('trongate.missing_i18n') ?>
        </p>
    </section>

    <section id="trongate_validation">
        <figure>
            <img alt="Trongate validation"
                 src="/blog_module/images/trongate_lvim_validation.png"
            >
            <figcaption>
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20"
                     fill="currentColor"
                     aria-hidden="true"
                >
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                          clip-rule="evenodd"
                    ></path>
                </svg>
                <?= $t('trongate.callback_validation') ?>
            </figcaption>
        </figure>
    </section>
    <section id="before-i-go">
        <h3><?= $t('trongate.before_i_go') ?></h3>
        <h2>Trongate MX</h2>
        <p><?= $t('trongate.mx') ?></p>
        <p><?= $t('trongate.mx_more') ?></p>
    </section>
</article>
<section id="author">
    <p class="text-left">// sasin91, 18.09.2024</p>
</section>
