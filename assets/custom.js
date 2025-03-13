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

// ALERT TIMEOUT FUNCTION
setTimeout(function () {
    let alertBox = document.getElementById("alertBox");
    if (alertBox) {
        alertBox.style.transition = "opacity 0.2s";
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.remove(), 500);
    }
}, 3000);


// FETCH POLICIES IN DROPDOWN FROM DATABASE;
document.getElementById("policyDropdown").addEventListener("change", function () {
    var policyId = this.value;
    fetchClauses("sub_control_policy", policyId, "subControlDropdown");
});

document.getElementById("subControlDropdown").addEventListener("change", function () {
    var subControlId = this.value;
    fetchClauses("linked_control_policy", subControlId, "linkedControlDropdown");
});

document.getElementById("linkedControlDropdown").addEventListener("change", function () {
    var linkedControlId = this.value;
    fetchClauses("inner_linked_control_policy", linkedControlId, "innerLinkedDropdown");
});

function fetchClauses(type, parentId, dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    dropdown.innerHTML = "<option value=''>Loading...</option>";

    fetch("fetch-clauses.php?type=" + type + "&parent_id=" + parentId)
        .then(response => response.json())
        .then(data => {
            dropdown.innerHTML = "<option value=''>Select Option</option>";
            data.forEach(clause => {
                let displayText = clause.number ? `${clause.number} - ${clause.name}` : clause.name;
                dropdown.innerHTML += `<option value="${clause.id}">${displayText}</option>`;
            });
        });
}

