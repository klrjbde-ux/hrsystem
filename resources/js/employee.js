// Wait until DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // 1️⃣ Employee table search
    const searchInput = document.getElementById("employeeSearch");
    const table = document.getElementById("employeeTable");
    if (searchInput && table) {
        searchInput.addEventListener("keyup", function () {
            const value = this.value.toLowerCase();
            const rows = table.querySelectorAll("tbody tr");
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });
    }

    // 2️⃣ SweetAlert delete confirmation
    if (window.jQuery) {
        $(".confirm-button").click(function (event) {
            event.preventDefault();
            const button = $(this);
            const id = button.data("id");
            const url = "/delete/" + id;

            swal({
                title: "Are you sure you want to delete this?",
                text: "It will be gone forever",
                icon: "warning",
                buttons: {
                    cancel: "Cancel",
                    confirm: {
                        text: "Yes",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                },
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = url;
                }
            });
        });
    }

    // 3️⃣ Hide success message after 4 seconds
    const message = document.getElementById("success-message");
    if (message) {
        setTimeout(() => {
            message.style.display = "none";
        }, 4000);
    }
});
