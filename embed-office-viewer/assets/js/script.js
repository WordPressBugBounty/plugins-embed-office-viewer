window.addEventListener("DOMContentLoaded", function () {
  const docs = document.querySelectorAll(".eov_doc");

  Object.values(docs).map((doc) => {
    loadFrameIfNotLoaded(doc);
  });

  function loadFrameIfNotLoaded(doc) {
    if (!doc) return false;
    const iframe = doc.querySelector("iframe");
    if (iframe && !iframe.contentWindow.length) {
      const source = iframe.src;
      iframe.src = source;
      setTimeout(() => {
        loadFrameIfNotLoaded(doc);
      }, 1500);
    }
  }
  loadFrameIfNotLoaded();
});
