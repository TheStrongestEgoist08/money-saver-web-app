<div x-show="openView"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

    <div @click.away="openView = false"
         class="bg-white rounded-lg p-6 w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Expense Details</h2>

        <div class="space-y-2 text-gray-700">
            <p><strong>Name:</strong> <span x-text="selected?.name"></span></p>
            <p><strong>Type:</strong> <span x-text="selected?.type"></span></p>
            <p><strong>Quantity:</strong> <span x-text="selected?.quantity"></span></p>
            <p><strong>Price:</strong> ₱<span x-text="selected?.price"></span></p>
            <p><strong>Total:</strong> ₱<span x-text="selected?.total"></span></p>
            <p><strong>Description:</strong> <span x-text="selected?.description"></span></p>
            <p><strong>Date:</strong> <span x-text="selected?.date"></span></p>
        </div>

        <div class="flex justify-end mt-4">
            <button @click="openView = false"
                class="px-4 py-2 bg-gray-600 text-white rounded">
                Close
            </button>
        </div>

    </div>
</div>
