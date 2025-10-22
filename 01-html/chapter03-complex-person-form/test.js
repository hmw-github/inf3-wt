test('Prüfe ob ein Formular mit Fieldset vorhanden ist', async () => {
    const formExists = await elementExists('form');
    const fieldsetExists = await elementExists('fieldset');
    expect(formExists, 'Es sollte ein form-Element vorhanden sein').toBe(true);
    expect(fieldsetExists, 'Es sollte ein fieldset-Element vorhanden sein').toBe(true);
});

test('Prüfe ob Name-Eingabefeld mit korrekten Attributen vorhanden ist', async () => {
    const nameInput = await page.evaluate(() => {
        const input = document.querySelector('input[type="text"]');
        return input ? {
            hasId: !!input.id,
            hasName: !!input.name,
            hasPlaceholder: !!input.placeholder
        } : null;
    });
    expect(nameInput, 'Text-Eingabefeld sollte vorhanden sein').toBeTruthy();
    expect(nameInput.hasId, 'Text-Eingabefeld sollte eine ID haben').toBe(true);
    expect(nameInput.hasName, 'Text-Eingabefeld sollte ein name-Attribut haben').toBe(true);
    expect(nameInput.hasPlaceholder, 'Text-Eingabefeld sollte einen Placeholder haben').toBe(true);
});

test('Prüfe ob Label für Name-Eingabefeld korrekt verknüpft ist', async () => {
    const labelConnection = await page.evaluate(() => {
        const input = document.querySelector('input[type="text"]');
        if (!input || !input.id) return false;
        const label = document.querySelector(`label[for="${input.id}"]`);
        return !!label;
    });
    expect(labelConnection, 'Text-Eingabefeld sollte ein verknüpftes Label haben').toBe(true);
});

test('Prüfe ob Textarea vorhanden ist', async () => {
    const textareaExists = await elementExists('textarea');
    expect(textareaExists, 'Es sollte eine Textarea vorhanden sein').toBe(true);

    const textareaAttribs = await page.evaluate(() => {
        const ta = document.querySelector('textarea');
        return ta ? {
            hasRows: !!ta.getAttribute('rows'),
            hasCols: !!ta.getAttribute('cols')
        } : null;
    });
    expect(textareaAttribs.hasRows, 'Textarea sollte rows-Attribut haben').toBe(true);
    expect(textareaAttribs.hasCols, 'Textarea sollte cols-Attribut haben').toBe(true);
});

test('Prüfe ob mindestens zwei Checkboxen vorhanden sind', async () => {
    const checkboxCount = await page.evaluate(() => document.querySelectorAll('input[type="checkbox"]').length);
    expect(checkboxCount, 'Es sollten mindestens zwei Checkboxen vorhanden sein').toBeGreaterThanOrEqual(2);
});

test('Prüfe ob alle Checkboxen korrekte Attribute haben', async () => {
    const checkboxes = await page.evaluate(() => {
        const checkboxElements = document.querySelectorAll('input[type="checkbox"]');
        return Array.from(checkboxElements).map(cb => ({
            hasId: !!cb.id,
            hasName: !!cb.name
        }));
    });

    expect(checkboxes.length, 'Es sollten mindestens 2 Checkboxen vorhanden sein').toBeGreaterThanOrEqual(2);
    checkboxes.forEach((checkbox, index) => {
        expect(checkbox.hasId, `Checkbox ${index + 1} sollte eine ID haben`).toBe(true);
        expect(checkbox.hasName, `Checkbox ${index + 1} sollte ein name-Attribut haben`).toBe(true);
    });
});

test('Prüfe ob alle Checkboxen verknüpfte Labels haben', async () => {
    const labelConnections = await page.evaluate(() => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        return Array.from(checkboxes).map(cb => {
            if (!cb.id) return false;
            const label = document.querySelector(`label[for="${cb.id}"]`);
            return !!label;
        });
    });

    expect(labelConnections.length, 'Es sollten mindestens 2 Checkboxen vorhanden sein').toBeGreaterThanOrEqual(2);
    labelConnections.forEach((connected, index) => {
        expect(connected, `Checkbox ${index + 1} sollte ein verknüpftes Label haben`).toBe(true);
    });
});

test('Prüfe ob mindestens drei Radiobuttons mit gleicher Gruppe vorhanden sind', async () => {
    const radioGroups = await page.evaluate(() => {
        const radios = document.querySelectorAll('input[type="radio"]');
        const groups = {};
        Array.from(radios).forEach(radio => {
            if (radio.name) {
                groups[radio.name] = (groups[radio.name] || 0) + 1;
            }
        });
        return groups;
    });

    const groupNames = Object.keys(radioGroups);
    expect(groupNames.length, 'Es sollte mindestens eine Radiobutton-Gruppe geben').toBeGreaterThanOrEqual(1);

    const largestGroup = Math.max(...Object.values(radioGroups));
    expect(largestGroup, 'Die größte Radiobutton-Gruppe sollte mindestens 3 Buttons haben').toBeGreaterThanOrEqual(3);
});

test('Prüfe ob alle Radiobuttons korrekte Attribute haben', async () => {
    const radiobuttons = await page.evaluate(() => {
        const radioElements = document.querySelectorAll('input[type="radio"]');
        return Array.from(radioElements).map(radio => ({
            hasId: !!radio.id,
            hasName: !!radio.name,
            hasValue: !!radio.value
        }));
    });

    expect(radiobuttons.length, 'Es sollten mindestens 3 Radiobuttons vorhanden sein').toBeGreaterThanOrEqual(3);
    radiobuttons.forEach((radio, index) => {
        expect(radio.hasId, `Radiobutton ${index + 1} sollte eine ID haben`).toBe(true);
        expect(radio.hasName, `Radiobutton ${index + 1} sollte ein name-Attribut haben`).toBe(true);
        expect(radio.hasValue, `Radiobutton ${index + 1} sollte ein value-Attribut haben`).toBe(true);
    });
});

test('Prüfe ob alle Radiobuttons verknüpfte Labels haben', async () => {
    const labelConnections = await page.evaluate(() => {
        const radios = document.querySelectorAll('input[type="radio"]');
        return Array.from(radios).map(radio => {
            if (!radio.id) return false;
            const label = document.querySelector(`label[for="${radio.id}"]`);
            return !!label;
        });
    });

    expect(labelConnections.length, 'Es sollten mindestens 3 Radiobuttons vorhanden sein').toBeGreaterThanOrEqual(3);
    labelConnections.forEach((connected, index) => {
        expect(connected, `Radiobutton ${index + 1} sollte ein verknüpftes Label haben`).toBe(true);
    });
});

test('Prüfe ob Select-Element mit korrekten Attributen vorhanden ist', async () => {
    const select = await page.evaluate(() => {
        const selectElement = document.querySelector('select');
        return selectElement ? {
            hasName: !!selectElement.name,
            optionCount: selectElement.options.length
        } : null;
    });

    expect(select, 'Select-Element sollte vorhanden sein').toBeTruthy();
    expect(select.hasName, 'Select sollte ein name-Attribut haben').toBe(true);
    expect(select.optionCount, 'Select sollte mindestens drei Optionen haben').toBeGreaterThanOrEqual(3);
});

test('Prüfe ob alle Select-Optionen value-Attribute haben', async () => {
    const options = await page.evaluate(() => {
        const selectElement = document.querySelector('select');
        return selectElement ? Array.from(selectElement.options).map(option => ({
            hasValue: !!option.value,
            hasText: !!option.textContent.trim()
        })) : [];
    });

    expect(options.length, 'Es sollten mindestens 3 Select-Optionen vorhanden sein').toBeGreaterThanOrEqual(3);
    options.forEach((option, index) => {
        expect(option.hasValue, `Option ${index + 1} sollte ein value-Attribut haben`).toBe(true);
        expect(option.hasText, `Option ${index + 1} sollte Text-Inhalt haben`).toBe(true);
    });
});

test('Prüfe ob Submit-Button vorhanden ist', async () => {
    const submitButtonExists = await elementExists('button[type="submit"]');
    expect(submitButtonExists, 'Submit-Button sollte vorhanden sein').toBe(true);
});

test('Prüfe ob Formular method="get" hat', async () => {
    const formMethod = await page.evaluate(() => {
        const form = document.querySelector('form');
        return form ? form.getAttribute('method') : null;
    });
    expect(formMethod, 'Formular sollte method="get" haben').toBe('get');
});