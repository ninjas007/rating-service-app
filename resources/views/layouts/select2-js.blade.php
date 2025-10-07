<script type="text/javascript">
    // load select options
    function loadSelectOptions({
        url,
        selectElement,
        placeholder = "-- Pilih --",
        selectedValue = null,
        getText = (item) => item.name
    }) {
        return new Promise((resolve, reject) => {
            const $select = typeof selectElement === "string" ? $(selectElement) : selectElement;
            $select.empty().append(`<option value="">${placeholder}</option>`);

            $.get(url)
                .done((res) => {
                    (res.collection || []).forEach(item => {
                        const selected = selectedValue === item.uid ? "selected" : "";
                        const text = getText(item);
                        $select.append(`<option value="${item.uid}" ${selected}>${text}</option>`);
                    });
                    resolve(); //
                })
                .fail(err => reject(err));
        });
    }

    function initSelect2SingleUsingMultiple(
        selector,
        url,
        placeholderText = "Select...",
        filtersKey = ["name"],
        showTexts = ["name"]
    ) {
        $(selector).select2({
            placeholder: placeholderText,
            width: "100%",
            allowClear: true,
            maximumSelectionLength: 1, // ❗ batasi hanya satu pilihan
            ajax: {
                url: url,
                dataType: "json",
                delay: 250,
                data: function(params) {
                    const filters = {};
                    filtersKey.forEach((key) => {
                        filters[key] = params.term || "";
                    });

                    return {
                        offset: 0,
                        limit: 10,
                        filters: filters,
                    };
                },
                processResults: function(data) {
                    return {
                        results: (data.collection || []).map((x) => ({
                            id: x.uid,
                            text: showTexts.map(f => x[f]).join(" - "),
                        })),
                    };
                },
                cache: true,
            }
        });
    }

    function initSelect2AjaxMultiple(
        selector,
        url,
        placeholderText = "Select...",
        filterKey = "name"
    ) {
        $(selector).select2({
            placeholder: placeholderText,
            width: "100%",
            allowClear: true,
            ajax: {
                url: url,
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        offset: 0,
                        limit: 10,
                        filters: {
                            [filterKey]: params.term || "",
                        },
                    };
                },
                processResults: function(data) {
                    return {
                        results: (data.collection || []).map((x) => ({
                            id: x.uid,
                            text: x.name,
                        })),
                    };
                },
                cache: true,
            },
        });
    }
</script>
