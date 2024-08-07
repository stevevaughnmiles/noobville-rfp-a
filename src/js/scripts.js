document.addEventListener('DOMContentLoaded', function () {
    const typeFilter = document.getElementById('content-type');
    const categoriesFilter = document.getElementById('categories');
    const taxonomyFilter = document.getElementById('taxonomy');
    const amenitiesFilter = document.getElementById('amenities');
    const dateFilter = document.getElementById('date');
    const timeFilter = document.getElementById('time');
    const gridItems = Array.from(document.getElementsByClassName('grid-item'));

    function getSelectedValues(selectElement) {
        return Array.from(selectElement.selectedOptions).map(option => option.value.toLowerCase());
    }

    function filterItems() {
        const selectedType = typeFilter.value.toLowerCase();
        const selectedCategories = getSelectedValues(categoriesFilter);
        const selectedTaxonomy = getSelectedValues(taxonomyFilter);
        const selectedAmenities = getSelectedValues(amenitiesFilter);
        const selectedDate = dateFilter.value;
        const selectedTime = timeFilter.value;

        console.log('Selected Type:', selectedType);
        console.log('Selected Categories:', selectedCategories);
        console.log('Selected Taxonomy:', selectedTaxonomy);
        console.log('Selected Amenities:', selectedAmenities);
        console.log('Selected Date:', selectedDate);
        console.log('Selected Time:', selectedTime);

        gridItems.forEach(item => {
            const type = item.getAttribute('data-type');
            const itemCategories = JSON.parse(item.getAttribute('data-categories') || '[]').map(c => c.toLowerCase());
            const itemTaxonomy = JSON.parse(item.getAttribute('data-taxonomy') || '[]').map(t => t.toLowerCase());
            const itemAmenities = JSON.parse(item.getAttribute('data-amenities') || '[]').map(a => a.toLowerCase());
            const itemDates = JSON.parse(item.getAttribute('data-dates') || '[]');
            const itemTimes = JSON.parse(item.getAttribute('data-times') || '[]');
            const itemListingIds = JSON.parse(item.getAttribute('data-listing-ids') || '[]');
            const itemEventIds = JSON.parse(item.getAttribute('data-event-ids') || '[]');

            let matches = true;

            if (selectedType !== 'all' && type !== selectedType) {
                matches = false;
            } else {
                const matchesCategories = selectedCategories.length === 0 || selectedCategories.every(c => itemCategories.includes(c));
                const matchesTaxonomy = selectedTaxonomy.length === 0 || selectedTaxonomy.every(t => itemTaxonomy.includes(t));
                const matchesAmenities = selectedAmenities.length === 0 || selectedAmenities.every(a => itemAmenities.includes(a));

                matches = matchesCategories && matchesTaxonomy && matchesAmenities;

                if (selectedDate) {
                    matches = matches && itemDates.some(d => d.includes(selectedDate));
                }

                if (selectedTime) {
                    matches = matches && itemTimes.some(t => t.includes(selectedTime));
                }
            }

            console.log('Item:', item, 'Matches:', matches);
            item.style.display = matches ? '' : 'none';
        });
    }

    typeFilter.addEventListener('change', filterItems);
    categoriesFilter.addEventListener('change', filterItems);
    taxonomyFilter.addEventListener('change', filterItems);
    amenitiesFilter.addEventListener('change', filterItems);
    dateFilter.addEventListener('input', filterItems);
    timeFilter.addEventListener('input', filterItems);

    // Initial filter to show all items
    filterItems();
});
