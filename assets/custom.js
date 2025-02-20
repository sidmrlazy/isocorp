$(document).ready(function () {
    $('#editorNew').summernote({
        height: 300, 
        minHeight: 150,
        maxHeight: 500,
        focus: true
    });

    $('form').on('submit', function () {
        $('#editorContent').val($('#editorNew').summernote('code'));
    });
});

// Main Policies page search box above the accordian
function searchPolicies() {
    let input = document.getElementById("searchInput").value.trim().toLowerCase();
    let accordionItems = document.querySelectorAll(".accordion-item");

    accordionItems.forEach(item => {
        let button = item.querySelector(".accordion-button");
        let body = item.querySelector(".accordion-body");
        let accordionCollapse = item.querySelector(".accordion-collapse");

        removeHighlights(button);
        removeHighlights(body);

        let buttonMatched = button && button.textContent.toLowerCase().includes(input);
        let bodyMatched = body && body.textContent.toLowerCase().includes(input);

        if (buttonMatched) highlightText(button, input);
        if (bodyMatched) highlightText(body, input);

        if (buttonMatched || bodyMatched) {
            button.classList.remove("collapsed");
            accordionCollapse.classList.add("show");
        } else {
            button.classList.add("collapsed");
            accordionCollapse.classList.remove("show");
        }
    });
}

function delayedReset() {
    setTimeout(() => {
        if (!document.activeElement.closest(".accordion-item")) {
            resetSearch();
        }
    }, 200);
}

function resetSearch() {
    let input = document.getElementById("searchInput");
    let accordionItems = document.querySelectorAll(".accordion-item");

    input.value = "";

    accordionItems.forEach(item => {
        let button = item.querySelector(".accordion-button");
        let body = item.querySelector(".accordion-body");
        let accordionCollapse = item.querySelector(".accordion-collapse");

        removeHighlights(button);
        removeHighlights(body);

        button.classList.add("collapsed");
        accordionCollapse.classList.remove("show");
    });
}

function highlightText(element, query) {
    if (!element || query === "") return;
    let regex = new RegExp(`(${query})`, "gi");

    element.childNodes.forEach(node => {
        if (node.nodeType === 3) { 
            let match = node.nodeValue.match(regex);
            if (match) {
                let span = document.createElement("span");
                span.innerHTML = node.nodeValue.replace(regex, `<span class="highlight">$1</span>`);
                node.replaceWith(span);
            }
        }
    });
}

function removeHighlights(element) {
    if (!element) return;
    element.querySelectorAll(".highlight").forEach(span => {
        span.replaceWith(document.createTextNode(span.textContent));
    });
}