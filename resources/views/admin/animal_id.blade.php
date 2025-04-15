<!-- Animal Registration Card with Drag Resize -->
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <!-- Header Section with Improved Title and Controls -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Animal Registration Card</h1>
                <p class="mt-2 text-gray-500">ID: <span class="font-mono text-lg font-bold text-green-800">{{ strtoupper($animal->animal_id) }}</span></p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
          
                <!-- Direct Download Buttons -->
                <div class="flex gap-2">
                    <button onclick="showDownloadMessage('PDF')" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"/>
                        </svg>
                        PDF
                    </button>
                    <button onclick="showDownloadMessage('PNG')" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        PNG
                    </button>
                    <button onclick="showDownloadMessage('JPG')" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        JPG
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced ID Card with Resizable Wrapper -->
        <div class="p-4 bg-gray-100 flex justify-center">
            <div id="resizable-wrapper" class="relative">
                <!-- The actual card -->
                <div id="animal-card" class="bg-white rounded-lg shadow-xl relative overflow-hidden border border-gray-300 font-sans transition-all duration-100 min-w-64 max-w-md">
                    <!-- Header Section with Bigger ID -->
                    <div class="bg-green-800 text-white py-2 px-3 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('assets/1.jpg') }}" class="w-8 h-8 rounded-full border border-yellow-400 object-cover">
                            <div>
                                <p class="text-sm font-semibold leading-tight">City Veterinary Office</p>
                                <p class="text-xs text-yellow-200 leading-tight">Valencia City Bukidnon</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-yellow-200 uppercase font-bold">Animal ID</p>
                            <p class="text-xl font-mono font-bold tracking-wider card-id-text">{{ strtoupper($animal->animal_id) }}</p>
                        </div>
                    </div>
            
                    <!-- Main Content -->
                    <div class="grid grid-cols-3 p-3">
                        <!-- Left Column - Photo -->
                        <div class="border-r border-gray-200 pr-2">
                            <div class="w-full aspect-square border border-gray-300 rounded overflow-hidden">
                                @if($animal->photo_front)
                                    <img src="{{ asset('storage/' . $animal->photo_front) }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Group Badge - Only shown when is_group = 1 -->
                            @if($animal->is_group == 1)
                            <div class="mt-2 bg-blue-100 rounded py-1 px-2 text-center">
                                <span class="text-blue-800 font-semibold">Group Registration</span>
                                <p class="text-blue-600 text-sm font-medium">{{ $animal->group_count ?? 'Multiple' }} Animals</p>
                            </div>
                            @endif
                        </div>
            
                        <!-- Right Column - Information -->
                        <div class="col-span-2 pl-2 relative">
                            <!-- QR Code in Upper Right Corner -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white p-1 rounded-bl border border-gray-300 qr-container">
                                {!! QrCode::size(72)->margin(0)->color(22, 82, 58)->generate(route('animal.id', $animal->animal_id)) !!}
                            </div>
            
                            <!-- Animal Details -->
                            <div class="pr-20"> <!-- Padding to avoid QR overlap -->
                                <div class="flex items-baseline gap-1 mb-1">
                                    <h2 class="text-lg font-bold uppercase tracking-tight text-gray-800 truncate animal-name">{{ $animal->name }}</h2>
                                    @if(!$animal->is_group)
                                        <span class="text-gray-500">({{ $animal->gender ? $animal->gender[0] : '-' }})</span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-x-2 gap-y-2">
                                    <div class="col-span-2 flex items-center gap-1">
                                        <span class="font-semibold text-green-800 species-text">{{ $animal->species->name ?? 'Species N/A' }}</span>
                                        @if($animal->breed && !$animal->is_group)
                                        <span class="text-yellow-600 bg-yellow-100 px-1 rounded breed-text">{{ $animal->breed->name }}</span>
                                        @endif
                                    </div>
                                    
                                    @if(!$animal->is_group)
                                    <div>
                                        <p class="text-gray-500 text-sm">Color:</p>
                                        <p class="font-medium">{{ $animal->color ?? 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-gray-500 text-sm">Age/DOB:</p>
                                        <p class="font-medium">
                                            @if($animal->birth_date)
                                                {{ (int)\Carbon\Carbon::parse($animal->birth_date)->diffInYears(now()) }}y
                                                ({{ $animal->birth_date->format('m/d/y') }})
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    @else
                                    <div>
                                        <p class="text-gray-500 text-sm">Count:</p>
                                        <p class="font-medium">{{ $animal->group_count ?? '0' }} animals</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-gray-500 text-sm">Group Type:</p>
                                        <p class="font-medium">{{ $animal->group_type ?? 'General' }}</p>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <p class="text-gray-500 text-sm">Owner:</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-gray-500 text-sm">Status:</p>
                                        <p class="font-medium">
                                            @if($animal->is_vaccinated == 1)
                                                <span class="text-green-600">Vaccinated</span>
                                            @elseif($animal->is_vaccinated == 2)
                                                <span class="text-yellow-600">Partially</span>
                                            @else
                                                <span class="text-red-600">Not Vaccinated</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
            
                            <!-- Owner Information -->
                            <div class="pt-2 border-t border-gray-200 mt-2">
                                <div class="flex gap-2 items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden shrink-0 owner-photo">
                                        @if(optional($animal->owner->user)->profile_image)
                                            <img src="{{ asset('storage/' . $animal->owner->user->profile_image) }}" class="w-full h-full object-cover" alt="Owner">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold truncate owner-name">{{ optional($animal->owner->user)->complete_name ?? 'Owner N/A' }}</p>
                                        <p class="text-gray-600 truncate owner-contact">{{ optional($animal->owner->user)->contact_no ?? 'Contact N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Footer -->
                    <div class="w-full bg-gray-100 py-2 px-3 flex justify-between items-center border-t border-gray-300">
                        <span class="font-mono text-gray-600 truncate">Registered: {{ $animal->created_at->format('m/d/y') }}</span>
                        <span class="text-green-800 font-medium">Valid until: {{ now()->addYear()->format('m/y') }}</span>
                    </div>
            
                    <!-- Security Features -->
                    <div class="absolute bottom-12 right-2 rotate-45 text-gray-200 text-xs font-bold tracking-widest opacity-50">
                        OFFICIAL
                    </div>

                    <!-- ID Number Watermark -->
                    <div class="absolute bottom-24 left-0 right-0 text-center watermark">
                        <p class="text-gray-100 opacity-10 font-mono text-5xl font-black tracking-widest watermark-text">
                            {{ strtoupper($animal->animal_id) }}
                        </p>
                    </div>
                </div>
                
                <!-- Resize handle -->
                <div id="resize-handle" class="absolute bottom-0 right-0 cursor-se-resize w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded-tl flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 22H16V16H22V22ZM22 15H15V8H22V15ZM15 22H8V15H15V22ZM8 8H15V1H8V8ZM7 15H1V8H7V15ZM7 22H1V16H7V22ZM7 7H1V1H7V7ZM16 7H8V1H16V7Z" />
                    </svg>
                </div>
                
                <!-- Size indicator popup that appears when resizing -->
                <div id="size-indicator" class="hidden absolute top-0 right-0 bg-black text-white text-xs px-2 py-1 rounded-bl-md opacity-75">
                    300px × 200px
                </div>
            </div>
        </div>
        
        <!-- Fixed notification message -->
        <div id="notification" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden"></div>
    </div>
</div>

<!-- Inline JavaScript for resize functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const animalCard = document.getElementById('animal-card');
        const resizeHandle = document.getElementById('resize-handle');
        const sizeIndicator = document.getElementById('size-indicator');
        let isResizing = false;
        let startWidth, startHeight, startX, startY;
        
        // Set initial size
        animalCard.style.width = '320px';
        updateResponsiveness();
        
        // Initialize resize functionality
        resizeHandle.addEventListener('mousedown', initResize);
        
        function initResize(e) {
            e.preventDefault();
            isResizing = true;
            
            // Get initial dimensions
            startWidth = animalCard.offsetWidth;
            startHeight = animalCard.offsetHeight;
            startX = e.clientX;
            startY = e.clientY;
            
            // Show size indicator
            sizeIndicator.classList.remove('hidden');
            updateSizeIndicator();
            
            // Add event listeners for drag
            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);
        }
        
        function resize(e) {
            if (!isResizing) return;
            
            // Calculate new width and height
            const newWidth = startWidth + (e.clientX - startX);
            // We're making the height respond to the width for this card to maintain proportions
            
            // Apply size constraints
            const constrainedWidth = Math.max(240, Math.min(480, newWidth));
            
            // Apply new dimensions
            animalCard.style.width = constrainedWidth + 'px';
            
            // Update size indicator
            updateSizeIndicator();
            
            // Update responsive elements
            updateResponsiveness();
        }
        
        function stopResize() {
            isResizing = false;
            document.removeEventListener('mousemove', resize);
            document.removeEventListener('mouseup', stopResize);
            
            // Hide size indicator
            sizeIndicator.classList.add('hidden');
            
            // Show notification
            showNotification('Card resized to ' + animalCard.offsetWidth + 'px width');
        }
        
        function updateSizeIndicator() {
            sizeIndicator.textContent = `${animalCard.offsetWidth}px width`;
        }
        
        function updateResponsiveness() {
            const width = animalCard.offsetWidth;
            
            // Scale text based on card width
            const nameElement = document.querySelector('.animal-name');
            const speciesElement = document.querySelector('.species-text');
            const breedElement = document.querySelector('.breed-text');
            const idElement = document.querySelector('.card-id-text');
            const ownerElement = document.querySelector('.owner-name');
            const contactElement = document.querySelector('.owner-contact');
            const watermarkElement = document.querySelector('.watermark-text');
            const qrContainer = document.querySelector('.qr-container');
            const ownerPhoto = document.querySelector('.owner-photo');
            
            // Adjust text sizes based on card width
            if (width < 280) {
                nameElement.classList.remove('text-lg');
                nameElement.classList.add('text-base');
                
                idElement.classList.remove('text-xl');
                idElement.classList.add('text-lg');
                
                watermarkElement.classList.remove('text-5xl');
                watermarkElement.classList.add('text-4xl');
                
                if (qrContainer) {
                    qrContainer.classList.remove('w-20', 'h-20');
                    qrContainer.classList.add('w-16', 'h-16');
                }
                
                if (ownerPhoto) {
                    ownerPhoto.classList.remove('w-8', 'h-8');
                    ownerPhoto.classList.add('w-6', 'h-6');
                }
            } else if (width > 400) {
                nameElement.classList.remove('text-base', 'text-lg');
                nameElement.classList.add('text-xl');
                
                idElement.classList.remove('text-lg');
                idElement.classList.add('text-xl');
                
                watermarkElement.classList.remove('text-4xl');
                watermarkElement.classList.add('text-6xl');
                
                if (qrContainer) {
                    qrContainer.classList.remove('w-16', 'h-16');
                    qrContainer.classList.add('w-24', 'h-24');
                }
                
                if (ownerPhoto) {
                    ownerPhoto.classList.remove('w-6', 'h-6');
                    ownerPhoto.classList.add('w-10', 'h-10');
                }
            } else {
                nameElement.classList.remove('text-base', 'text-xl');
                nameElement.classList.add('text-lg');
                
                idElement.classList.remove('text-lg');
                idElement.classList.add('text-xl');
                
                watermarkElement.classList.remove('text-4xl', 'text-6xl');
                watermarkElement.classList.add('text-5xl');
                
                if (qrContainer) {
                    qrContainer.classList.remove('w-16', 'h-16', 'w-24', 'h-24');
                    qrContainer.classList.add('w-20', 'h-20');
                }
                
                if (ownerPhoto) {
                    ownerPhoto.classList.remove('w-6', 'h-6', 'w-10', 'h-10');
                    ownerPhoto.classList.add('w-8', 'h-8');
                }
            }
        }
    });
    
    // Function to show download message
    function showDownloadMessage(format) {
        // Get animal ID
        var animalIdElement = document.querySelector('.card-id-text');
        var animalId = animalIdElement ? animalIdElement.innerText : 'unknown';
        
        // Create a form for downloading
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/animals/' + animalId + '/download';
        
        // Add CSRF token
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Add format parameter
        var formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format.toLowerCase();
        form.appendChild(formatInput);
        
        // Append form to document and submit
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        // Show notification
        showNotification('Downloading animal ID card as ' + format);
    }
    
    // Function to show notification
    function showNotification(message) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.classList.remove('hidden');

        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    function showDownloadMessage(format) {
        showNotification(`Preparing ${format} download...`);
        // Optional: trigger actual download here
    }
</script>