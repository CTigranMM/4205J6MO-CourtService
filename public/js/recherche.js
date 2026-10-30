const input = document.querySelector('#search');
const results = document.querySelector('#search-results');
const status = document.querySelector('#search-status');

if (input && results && status) {
    let timer;
    let controller;

    input.addEventListener('input', () => {
        clearTimeout(timer);
        controller?.abort();
        results.replaceChildren();

        const term = input.value.trim();

        if (term.length < 2) {
            status.textContent = '';
            return;
        }

        status.textContent = input.dataset.searching;

        timer = setTimeout(async () => {
            controller = new AbortController();

            try {
                const url = new URL(input.dataset.searchUrl, window.location.origin);
                url.searchParams.set('q', term);

                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                    },
                    signal: controller.signal,
                });

                if (!response.ok) {
                    throw new Error('Recherche indisponible');
                }

                const payload = await response.json();

                status.textContent = payload.data.length
                    ? input.dataset.count.replace(':count', payload.data.length)
                    : input.dataset.empty;

                for (const item of payload.data) {
                    const link = document.createElement('a');
                    link.href = item.url;
                    link.textContent = item.label;

                    const row = document.createElement('li');
                    row.append(link);
                    results.append(row);
                }
            } catch (error) {
                if (error.name !== 'AbortError') {
                    status.textContent = input.dataset.error;
                }
            }
        }, 250);
    });
}
