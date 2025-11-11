<article id="blog" class="container">
    <h2 class="text-center text-fancy"><?= $t('headline') ?></h2>

    <section id="content">
        <div class="gradient-container">
            <div class="gradient-blur"></div>
            <div class="gradient-overlay"></div>
            <div class="content-wrapper">
                <article class="article-content">
                    <div class="article-box">
                        <div class="article-header">
                            <h3 class="article-title">
                                <a href="blog/trongate">Trongate PHP</a>
                            </h3>
                            <time datetime="2024-09-14" class="article-date">14.9.2024</time>
                        </div>
                        <div class="article-image">
                            <img src="blog_module/images/the_framework.webp" alt="Trongate: The framework they don't want you to know about" />
                        </div>
                        <p class="article-text">
                          <?= nl2br($t('trongate.summary')) ?>
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</article>