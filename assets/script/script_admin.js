const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

// Fungsi untuk menentukan menu aktif
const sidebarLinks = document.querySelectorAll(".sidebar-link");
const currentUrl = window.location.pathname;

// Logika menu aktif berdasarkan URL
sidebarLinks.forEach((link) => {
  if (currentUrl.includes(link.getAttribute("href"))) {
    // Hapus kelas 'active' dari semua elemen
    sidebarLinks.forEach((link) =>
      link.parentElement.classList.remove("active")
    );
    // Tambahkan kelas 'active' ke menu yang cocok
    link.parentElement.classList.add("active");
  }
});

// Tambahkan event click untuk memperbarui menu aktif
sidebarLinks.forEach((link) => {
  link.addEventListener("click", () => {
    // Hapus kelas 'active' dari semua elemen
    sidebarLinks.forEach((link) =>
      link.parentElement.classList.remove("active")
    );
    // Tambahkan kelas 'active' ke menu yang diklik
    link.parentElement.classList.add("active");
  });
});
