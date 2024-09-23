(function () {
    class USFlag extends HTMLElement {
        constructor() {
            super();
            this.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" viewBox="0 0 640 480">
        <g fill-rule="evenodd">
          <path fill="#b22234" d="M0 0h640v480H0z"/>
          <path fill="#fff" d="M0 0h640v40H0zm0 80h640v40H0zm0 80h640v40H0zm0 80h640v40H0zm0 80h640v40H0zm0 80h640v40H0z"/>
          <path fill="#3c3b6e" d="M0 0h256v240H0z"/>
          <path fill="#fff" d="M0 0l15 10-15 10zM0 20l15 10-15 10zM0 40l15 10-15 10zM0 60l15 10-15 10zM0 80l15 10-15 10zM0 100l15 10-15 10zM0 120l15 10-15 10zM0 140l15 10-15 10zM0 160l15 10-15 10zM0 180l15 10-15 10zM0 200l15 10-15 10zM0 220l15 10-15 10zM0 240l15 10-15 10z"/>
          <g fill="#fff" transform="translate(0, 10)">
            <path d="M0 0l15 10-15 10zM0 20l15 10-15 10zM0 40l15 10-15 10zM0 60l15 10-15 10zM0 80l15 10-15 10zM0 100l15 10-15 10zM0 120l15 10-15 10zM0 140l15 10-15 10zM0 160l15 10-15 10zM0 180l15 10-15 10zM0 200l15 10-15 10zM0 220l15 10-15 10z"/>
          </g>
        </g>
      </svg>
    `;
        }
    }

    class DKFlag extends HTMLElement {
        constructor() {
            super();
            this.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" viewBox="0 0 16 10">
        <rect width="16" height="10" fill="#c8102e"/>
        <rect width="2" height="10" x="5" fill="#fff"/>
        <rect width="16" height="2" y="4" fill="#fff"/>
      </svg>
    `;
        }
    }

    class LanguageSelector extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });

            this.shadowRoot.innerHTML = `
      <style>
        :host {
          font-family: Arial, sans-serif;
        }

        .language-selector {
          position: relative;
        }

        .language-button {
          background: transparent;
          border: none;
          color: var(--alt);
          padding: 10px;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: space-between;
        }
        
        .language-button svg {
            margin-right: 10px;
        }

        .language-list {
          position: absolute;
          background-color: var(--background);
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
          z-index: 99;
          display: none; /* Start closed */
          width: 160px;
          list-style: none;
          padding: 0;
          margin: 0;
        }

        .language-list li {
          padding: 0;
        }

        .language-list button {
          width: 100%;
          padding: 10px;
          background: none;
          border: none;
          display: flex;
          align-items: center;
          cursor: pointer;
          color: var(--alt);          
        }
        
        .language-list button svg {
          margin-right: 10px;
        }

        button:hover {
          background-color: var(--primary);
        }

        .block {
          display: block; /* Show when open */
        }
      </style>

      <nav class="language-selector" aria-label="Language selector">
        <button class="language-button" aria-expanded="false" aria-haspopup="listbox" id="languageButton">
          <dk-flag></dk-flag>
          <span>Danish</span>
        </button>
        <ul class="language-list" role="listbox" aria-labelledby="languageButton">
          <li role="option" tabindex="0" aria-selected="false">
            <button type="button" data-value="en">
              <us-flag></us-flag>
              <span>English (US)</span>
            </button>
          </li>
          <li role="option" tabindex="0" aria-selected="false">
            <button type="button" data-value="da">
              <dk-flag></dk-flag>
              <span>Danish</span>
            </button>
          </li>
        </ul>
      </nav>
    `;
        }

        connectedCallback() {
            this.updateSelectedLanguage();

            const languageButton = this.shadowRoot.querySelector('.language-button');
            const itemsFlag = this.shadowRoot.querySelector('.language-list');
            const options = this.shadowRoot.querySelectorAll('.language-list button');

            languageButton.addEventListener('click', (e) => {
                const expanded = languageButton.getAttribute('aria-expanded') === 'true';

                if (expanded) {
                    languageButton.setAttribute('aria-expanded', 'false');
                    itemsFlag.classList.remove('block');
                } else {
                    languageButton.setAttribute('aria-expanded', 'true');
                    itemsFlag.classList.add('block');
                }

                // Prevent the document click event from firing immediately
                e.stopPropagation();
            });

            options.forEach(option => {
                option.addEventListener('click', () => {
                    const selectedLanguage = option.getAttribute('data-value');
                    this.updateQueryString(selectedLanguage);
                    itemsFlag.classList.remove('block');
                    location.reload();
                });
            });

            document.addEventListener('click', (e) => {
                if (!languageButton.contains(e.target) && !itemsFlag.contains(e.target)) {
                    languageButton.setAttribute('aria-expanded', 'false');
                    itemsFlag.classList.remove('block'); // Close the menu when clicking outside
                }
            });
        }

        updateSelectedLanguage() {
            const urlParams = new URLSearchParams(window.location.search);
            const lang = urlParams.get('lang') || 'da'; // Default to Danish if no param is set
            const selectedOption = this.shadowRoot.querySelector(`button[data-value="${lang}"]`);

            if (selectedOption) {
                this.shadowRoot.querySelector('.language-button').innerHTML = selectedOption.innerHTML;
                this.shadowRoot.querySelectorAll('.language-list button').forEach(opt => {
                    const isSelected = opt.getAttribute('data-value') === lang;
                    opt.parentNode.setAttribute('aria-selected', isSelected);
                });
            }
        }

        updateQueryString(lang) {
            const url = new URL(window.location);
            url.searchParams.set('lang', lang);
            window.history.pushState({}, '', url); // Update the query string without reloading
        }
    }

    customElements.define('us-flag', USFlag);
    customElements.define('dk-flag', DKFlag);
    customElements.define('language-selector', LanguageSelector);
})();