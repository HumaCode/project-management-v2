/* ============================================================
   UI COMPONENTS (LOADING, EMPTY, ERROR, PAGINATION)
   File: public/js/custom-table.js
============================================================ */

// Helper: Menghitung jumlah kolom dinamis dari thead tabel
window.getTableColSpan = function () {
    let cols = $("#dataBody").closest("table").find("thead th").length;
    return cols > 0 ? cols : 10; // Fallback ke 10 jika thead tidak ditemukan
};

window.renderLoading = function (count = 5) {
    let colspan = window.getTableColSpan();
    let html = "";
    for (let i = 0; i < count; i++) {
        html += `
            <tr>
                <td colspan="${colspan}">
                    <div class="skeleton" style="height: 60px; margin: 5px 0; border-radius: 12px; opacity: 0.1; background: #ccc;"></div>
                </td>
            </tr>
        `;
    }
    $("#dataBody").html(html);
};

window.renderEmpty = function (message = "Data tidak ditemukan") {
    let colspan = window.getTableColSpan();
    $("#dataBody").html(`
        <tr>
            <td colspan="${colspan}" class="text-center" style="padding: 50px 0;">
                <div class="empty-state">
                    <i class="bi bi-folder-x" style="font-size: 40px; color: #666;"></i>
                    <p style="margin-top: 10px; color: #888;">${message}</p>
                </div>
            </td>
        </tr>
    `);
};

window.renderError = function (message = "Terjadi kesalahan sistem") {
    let colspan = window.getTableColSpan();
    $("#dataBody").html(`
        <tr>
            <td colspan="${colspan}" class="text-center" style="padding: 40px;">
                <div class="alert alert-danger d-inline-block">
                    <i class="bi bi-exclamation-triangle-fill"></i> ${message}
                </div>
            </td>
        </tr>
    `);
};

window.renderInfo = function (meta) {
    $(".tbl-info").html(
        `Menampilkan <b>${meta.from || 0}</b> - <b>${meta.to || 0}</b> dari <b>${meta.total || 0}</b> data`,
    );
};

window.renderPagination = function (meta) {
    const $pagination = $(".pag");
    $pagination.empty();

    const current = meta.current_page;
    const last = meta.last_page;
    const delta = 1;
    const range = [];

    // Tombol Previous
    $pagination.append(`
        <button class="pb" data-page="prev" ${current === 1 ? "disabled" : ""}>
            <i class="bi bi-chevron-left"></i>
        </button>
    `);

    for (let i = 1; i <= last; i++) {
        if (
            i === 1 ||
            i === last ||
            (i >= current - delta && i <= current + delta)
        ) {
            range.push(i);
        }
    }

    let prevPage;
    for (let i of range) {
        if (prevPage) {
            if (i - prevPage === 2) {
                $pagination.append(
                    `<button class="pb" data-page="${prevPage + 1}">${prevPage + 1}</button>`,
                );
            } else if (i - prevPage !== 1) {
                $pagination.append(`<span class="pag-dot">&hellip;</span>`);
            }
        }
        $pagination.append(`
            <button class="pb ${i === current ? "active" : ""}" data-page="${i}">${i}</button>
        `);
        prevPage = i;
    }

    // Tombol Next
    $pagination.append(`
        <button class="pb" data-page="next" ${current === last ? "disabled" : ""}>
            <i class="bi bi-chevron-right"></i>
        </button>
    `);
};

/* ============================================================
   EVENTS HANDLER GLOBAL (SEARCH, PAGINATION CLICK)
============================================================ */
$(function () {
    // Pastikan loadData dan tableState ada sebelum mendaftarkan event
    if (typeof window.loadData === "function" && window.tableState) {
        // Search dengan Debounce
        const debounceReload =
            typeof _ !== "undefined"
                ? _.debounce(() => {
                      window.tableState.page = 1;
                      window.loadData();
                  }, 500)
                : function () {
                      // Fallback jika tidak ada lodash
                      clearTimeout(window.searchTimeout);
                      window.searchTimeout = setTimeout(() => {
                          window.tableState.page = 1;
                          window.loadData();
                      }, 500);
                  };

        $("#searchInput").on("input", function () {
            window.tableState.search = $(this).val();
            debounceReload();
        });

        // Click Pagination
        $(document).on("click", ".pag .pb", function () {
            const page = $(this).data("page");
            if (!page || $(this).prop("disabled") || $(this).hasClass("active"))
                return;

            if (page === "prev") {
                window.tableState.page--;
            } else if (page === "next") {
                window.tableState.page++;
            } else {
                window.tableState.page = Number(page);
            }

            window.loadData();
        });

        // Change Per Page (Tampil Data)
        $("#tampilData").on("change", function () {
            window.tableState.per_page = $(this).val();
            window.tableState.page = 1;
            window.loadData();
        });
    }
});
