        <footer>
            <div>
                <div id="footer-logo">
                    <div>
                        <a href="/"><img src="/images/logo.png" alt="лого" width="83" height="60"></a>
                    </div>
                    <div>
                        <strong>За нас</strong>
                        <span>Директория с български сайтове, кратка информация и данни за контакт.</span>
                    </div>
                </div>
                <div>
                    <strong>Информация</strong>
                    <ul>
                        <li><a href="/" class="active">Начало</a></li>
                        <li><a href="/add">Добави сайт безплатно</a></li>
                        <li><a href="/contacts">Контакти</a></li>
                    </ul>
                </div>
            </div>
        </footer>
        <div id="top" class="hidden">
            <a href="#top">
                <svg width="36px" height="36px" viewBox="0 0 48 48" fill="none">
                    <rect width="36" height="36" fill="white" fill-opacity="0.01"/>
                    <path  d="M13 30L25 18L37 30" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        <?php
        if (ENABLE_CSS_JS_MINIFICATION)
        {
        ?>
        <script src="/js/min/main.min.<?php echo APP_VERSION; ?>.js"></script>
        <?php
        }
        else
        {
        ?>
        <script src="/js/main.js?v=<?php echo APP_VERSION; ?>"></script>
        <?php
        }
        ?>
    </body>
</html>