$(() => {
    // Fetch latest modules
    $('[data-modules]').each((index, element) => {
        let modules = $(element).data('modules').split(';');

        for (let module of modules) {
            if (!module) {
                continue;
            }

            if (undefined === window[module]) {
                console.warn(`[main.js] Module ${module} is not defined yet !`);

                continue;
            }

            window[module]($(element));
        }
    });
});
