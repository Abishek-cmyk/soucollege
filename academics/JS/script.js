document.addEventListener("DOMContentLoaded", function () {

  // close all open menus
  function closeAll() {
    document.querySelectorAll(".has-sub, .menu-item").forEach(item => {
      item.classList.remove("open");
      // hide inline-style submenus (defensive)
      const sm = item.querySelector(".sub-menu, .inner-sub");
      if (sm) sm.style.display = "";
    });
  }

  // toggle a single li (.has-sub or .menu-item)
  function toggleItem(li) {

    const isOpen = li.classList.contains("open");

    // Step 1: Close ONLY siblings, not whole menu
    const parent = li.parentElement;
    parent.querySelectorAll(":scope > li.open").forEach(other => {
        if (other !== li) other.classList.remove("open");
    });

    // Step 2: Toggle current item
    if (!isOpen) {
        li.classList.add("open");
    } else {
        li.classList.remove("open");
    }
}


  // handle arrow clicks (arrow is inside <a>)
  document.querySelectorAll(".main-menu .arrow").forEach(arrow => {
    arrow.addEventListener("click", function (e) {
      e.preventDefault();   // prevent anchor navigation when clicking arrow
      e.stopPropagation();

      const li = this.closest(".has-sub, .menu-item");
      if (!li) return;
      // toggle only the clicked item
      if (li.classList.contains("open")) {
        li.classList.remove("open");
      } else {
        toggleItem(li);
      }
    });
  });

  // clicking outside closes menus
  document.addEventListener("click", function (e) {

    // If click happens inside main-menu, OR inside sub-menu, OR inner-sub → DO NOT CLOSE
    if (
        e.target.closest(".main-menu") ||
        e.target.closest(".sub-menu") ||
        e.target.closest(".inner-sub")
    ) {
        return;
    }

    // Clicked outside → close all
    closeAll();
});


  // optional: if user presses ESC close all
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeAll();
  });

  // prevent anchor default only for arrows; normal anchor clicks navigate as usual.
  // (No extra listener needed; arrow click already prevents default there.)

});


