// AmyO Parks JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            const expanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            mobileMenuButton.setAttribute('aria-expanded', !expanded);
        });
    }
    
    // Search functionality with debouncing
    const searchInputs = document.querySelectorAll('.search-input');
    let searchTimeout;
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this);
            }, 300);
        });
    });
    
    // Dynamic city/zip loading based on state selection
    const stateSelects = document.querySelectorAll('.state-select');
    stateSelects.forEach(select => {
        select.addEventListener('change', function() {
            loadCitiesByState(this.value);
        });
    });
    
    const citySelects = document.querySelectorAll('.city-select');
    citySelects.forEach(select => {
        select.addEventListener('change', function() {
            loadZipCodesByCity(this.value);
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Dynamic attribute fields in admin forms
    const addAttributeButtons = document.querySelectorAll('.add-attribute-btn');
    addAttributeButtons.forEach(button => {
        button.addEventListener('click', addAttributeField);
    });
    
    // Remove attribute field buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-attribute-btn')) {
            removeAttributeField(e.target);
        }
    });
    
    // Confirmation dialogs for delete actions
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide flash messages
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
    
    // Initialize tooltips (if any)
    initializeTooltips();
    
    // Initialize image lazy loading
    initializeLazyLoading();
});

// Search functionality
function performSearch(input) {
    const searchTerm = input.value.trim();
    const resultsContainer = document.getElementById('search-results');
    
    if (searchTerm.length < 2) {
        if (resultsContainer) {
            resultsContainer.innerHTML = '';
        }
        return;
    }
    
    // Show loading spinner
    if (resultsContainer) {
        resultsContainer.innerHTML = '<div class="flex justify-center p-4"><div class="spinner"></div></div>';
    }
    
    // Perform AJAX search
    fetch('/amyoparks/search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `search=${encodeURIComponent(searchTerm)}&ajax=1`
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Search error:', error);
        if (resultsContainer) {
            resultsContainer.innerHTML = '<p class="text-red-600 p-4">Search failed. Please try again.</p>';
        }
    });
}

// Display search results
function displaySearchResults(data) {
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;
    
    if (data.parks && data.parks.length > 0) {
        let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
        data.parks.forEach(park => {
            html += `
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="/amyoparks/park-details.php?park_id=${park.park_id}" class="text-primary hover:text-accent">
                                ${escapeHtml(park.name)}
                            </a>
                        </h3>
                        <p class="text-gray-600">${escapeHtml(park.city)}, ${escapeHtml(park.state)}</p>
                        <p class="text-sm text-gray-500 mt-2">${park.amenity_count} amenities</p>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        resultsContainer.innerHTML = html;
    } else {
        resultsContainer.innerHTML = '<p class="text-gray-600 p-4">No parks found matching your search.</p>';
    }
}

// Load cities by state
function loadCitiesByState(stateId) {
    const citySelect = document.querySelector('.city-select');
    const zipSelect = document.querySelector('.zip-select');
    
    if (!citySelect || !stateId) return;
    
    // Clear existing options
    citySelect.innerHTML = '<option value="">Select City...</option>';
    if (zipSelect) {
        zipSelect.innerHTML = '<option value="">Select Zip Code...</option>';
    }
    
    fetch('/admin/ajax/get-cities.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `state_id=${encodeURIComponent(stateId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.cities) {
            data.cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.city_id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading cities:', error);
    });
}

// Load zip codes by city
function loadZipCodesByCity(cityId) {
    const zipSelect = document.querySelector('.zip-select');
    
    if (!zipSelect || !cityId) return;
    
    // Clear existing options
    zipSelect.innerHTML = '<option value="">Select Zip Code...</option>';
    
    fetch('/admin/ajax/get-zip-codes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `city_id=${encodeURIComponent(cityId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.zip_codes) {
            data.zip_codes.forEach(zip => {
                const option = document.createElement('option');
                option.value = zip.zip_code_id;
                option.textContent = zip.code;
                zipSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading zip codes:', error);
    });
}

// Form validation
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    // Clear previous errors
    form.querySelectorAll('.error-message').forEach(error => error.remove());
    form.querySelectorAll('.border-red-500').forEach(field => {
        field.classList.remove('border-red-500');
    });
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            // Specific validation based on field type
            if (field.type === 'email' && !isValidEmail(field.value)) {
                showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            }
            
            if (field.type === 'url' && field.value && !isValidUrl(field.value)) {
                showFieldError(field, 'Please enter a valid URL');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('border-red-500');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message text-red-600 text-sm mt-1';
    errorDiv.textContent = message;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}

// Add attribute field
function addAttributeField() {
    const container = document.getElementById('attributes-container');
    if (!container) return;
    
    const attributeCount = container.children.length;
    const newField = document.createElement('div');
    newField.className = 'flex space-x-2 mb-2 attribute-field';
    newField.innerHTML = `
        <select name="attributes[${attributeCount}][type_id]" class="form-select flex-1">
            <option value="">Select Attribute Type...</option>
            ${getAttributeTypeOptions()}
        </select>
        <input type="text" name="attributes[${attributeCount}][value]" placeholder="Attribute Value" class="form-input flex-1">
        <button type="button" class="remove-attribute-btn btn-danger px-3 py-2">Remove</button>
    `;
    
    container.appendChild(newField);
}

// Remove attribute field
function removeAttributeField(button) {
    const field = button.closest('.attribute-field');
    if (field) {
        field.remove();
        // Reindex remaining fields
        reindexAttributeFields();
    }
}

// Reindex attribute fields
function reindexAttributeFields() {
    const fields = document.querySelectorAll('.attribute-field');
    fields.forEach((field, index) => {
        const selects = field.querySelectorAll('select');
        const inputs = field.querySelectorAll('input');
        
        selects.forEach(select => {
            select.name = select.name.replace(/\[\d+\]/, `[${index}]`);
        });
        
        inputs.forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
        });
    });
}

// Get attribute type options (to be populated from PHP)
function getAttributeTypeOptions() {
    // This would be populated from PHP in a real implementation
    // For now, return empty string
    return '';
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Initialize tooltips
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

// Show tooltip
function showTooltip(e) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip absolute bg-gray-800 text-white p-2 rounded text-sm z-50 pointer-events-none';
    tooltip.textContent = e.target.getAttribute('data-tooltip');
    
    document.body.appendChild(tooltip);
    
    const rect = e.target.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    
    e.target.tooltipElement = tooltip;
}

// Hide tooltip
function hideTooltip(e) {
    if (e.target.tooltipElement) {
        e.target.tooltipElement.remove();
        e.target.tooltipElement = null;
    }
}

// Initialize lazy loading for images
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        images.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
    }
}

// Pagination helper
function goToPage(page) {
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

// Export functions for global use
window.AmyOParks = {
    performSearch,
    loadCitiesByState,
    loadZipCodesByCity,
    validateForm,
    goToPage
};
