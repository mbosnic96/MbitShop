<x-app-layout>
<div class="flex h-screen">
    @include('dashboard.sidebar')
    @include('dashboard.tabs')
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-pane');

        // Retrieve the last active tab from localStorage
        let activeTab = localStorage.getItem("activeTab") || "tad-dashboard"; // Default tab

        // Function to activate a tab
        function activateTab(tab) {
            // Update localStorage
            localStorage.setItem("activeTab", tab);
            // Remove active styles from all buttons
            tabButtons.forEach(button => {
                button.classList.remove('bg-gray-900');
                button.classList.add('bg-gray-800');
            });

            // Add active styles to the clicked tab
            tabButtons.forEach(button => {
                if (button.textContent.toLowerCase() === tab) {
                    button.classList.add('bg-gray-900');
                    button.classList.remove('bg-gray-800');
                }
            });

            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));

            // Show the selected tab content
            const activeContent = document.getElementById(`tab-${tab}`);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }

        // Set the correct tab on page load
        activateTab(activeTab);

        // Add event listeners to tab buttons
        tabButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                let selectedTab = event.target.textContent.toLowerCase();
                activateTab(selectedTab);
            });
        });
    });
</script>

</x-app-layout>
