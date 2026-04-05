<template>
  <div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="booking" class="space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">Checkout</h1>
            <p class="mt-2 text-gray-600">Complete your payment to confirm booking #{{ booking.id }}</p>
        </div>

        <!-- Booking Summary -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
           <div class="px-4 py-5 sm:px-6">
             <h3 class="text-lg leading-6 font-medium text-gray-900">Booking Summary</h3>
           </div>
           <div class="border-t border-gray-200">
             <dl>
               <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                 <dt class="text-sm font-medium text-gray-500">Tour</dt>
                 <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ booking.tour_title || 'Tour Package' }}</dd>
               </div>
               <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                 <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                 <dd class="mt-1 text-sm font-bold text-gray-900 sm:mt-0 sm:col-span-2">₹{{ booking.total_price }}</dd>
               </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                 <dt class="text-sm font-medium text-gray-500">Status</dt>
                 <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 capitalize">{{ booking.status }}</dd>
               </div>
             </dl>
           </div>
        </div>

        <!-- Payment Method Selection -->
         <div class="bg-white shadow sm:rounded-lg p-6">
             <h3 class="text-lg font-medium text-gray-900 mb-4">Select Payment Method</h3>
             
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <!-- Razorpay -->
                 <div 
                    @click="paymentMethod = 'razorpay'"
                    :class="{'ring-2 ring-indigo-500': paymentMethod === 'razorpay', 'border-gray-200': paymentMethod !== 'razorpay'}"
                    class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 flex flex-col items-center justify-center h-32"
                 >
                     <p class="font-bold text-lg">Razorpay</p>
                     <span class="text-xs text-gray-500">(INR Cards, UPI, Netbanking)</span>
                 </div>

                 <!-- Stripe -->
                  <div 
                    @click="paymentMethod = 'stripe'"
                    :class="{'ring-2 ring-indigo-500': paymentMethod === 'stripe', 'border-gray-200': paymentMethod !== 'stripe'}"
                    class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 flex flex-col items-center justify-center h-32"
                 >
                     <p class="font-bold text-lg">Stripe</p>
                     <span class="text-xs text-gray-500">(International Cards)</span>
                 </div>
             </div>
         </div>

         <!-- Stripe Elements or Pay Button -->
         <div v-if="paymentMethod === 'stripe'" class="bg-white shadow sm:rounded-lg p-6">
             <div id="stripe-element-mount-point" class="mb-4"></div>
             <button 
                @click="handleStripePayment" 
                :disabled="processing"
                class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-indigo-700 disabled:opacity-50"
             >
                 {{ processing ? 'Processing...' : `Pay ₹${booking.total_price} via Stripe` }}
             </button>
         </div>

         <div v-if="paymentMethod === 'razorpay'" class="bg-white shadow sm:rounded-lg p-6 text-center">
             <p class="mb-4 text-gray-600">You will be redirected to Razorpay secure checkout.</p>
              <button 
                @click="handleRazorpayPayment" 
                :disabled="processing"
                class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-blue-700 disabled:opacity-50"
             >
                 {{ processing ? 'Processing...' : `Pay ₹${booking.total_price} via Razorpay` }}
             </button>
         </div>

      </div>
      <div v-else class="text-center py-12">
          Loading checkout details...
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import { loadStripe } from '@stripe/stripe-js'

const route = useRoute()
const router = useRouter()
const booking = ref(null)
const paymentMethod = ref('razorpay')
const processing = ref(false)

// Stripe
let stripe = null
let elements = null
let cardElement = null

// Razorpay Script Loader
const loadRazorpayScript = () => {
    return new Promise((resolve) => {
        const script = document.createElement('script')
        script.src = 'https://checkout.razorpay.com/v1/checkout.js'
        script.onload = () => resolve(true)
        script.onerror = () => resolve(false)
        document.body.appendChild(script)
    })
}

const config = ref({})

onMounted(async () => {
    try {
        // Fetch Public Settings
        const configRes = await api.get('/settings/public')
        config.value = configRes.data

        const id = route.params.id
        const res = await api.get(`/bookings/${id}`)
        booking.value = res.data

        // Initialize Stripe with dynamic key
        if(config.value.stripe_publishable_key) {
            stripe = await loadStripe(config.value.stripe_publishable_key)
        } else {
            console.warn("Stripe Publishable Key not found in settings")
        }
    } catch (e) {
        console.error("Failed to load booking or config", e)
        alert("Failed to load booking details")
    }
})

// Watch for Stripe selection to mount elements
watch(paymentMethod, async (newMethod) => {
    if (newMethod === 'stripe' && !cardElement) {
        await nextTick()
        elements = stripe.elements()
        cardElement = elements.create('card')
        cardElement.mount('#stripe-element-mount-point')
    }
})

const handleRazorpayPayment = async () => {
    processing.value = true
    const res = await loadRazorpayScript()
    if (!res) {
        alert('Razorpay SDK failed to load. Are you online?')
        processing.value = false
        return
    }

    try {
        // Create Order
        const orderRes = await api.post('/payment/razorpay/create-order', {
             booking_id: booking.value.id
        })
        const orderData = orderRes.data

        const options = {
            key: config.value.razorpay_key_id, 
            amount: orderData.amount,
            currency: orderData.currency,
            name: "Incredible India Tours",
            description: `Payment for Booking #${booking.value.id}`,
            order_id: orderData.id,
            handler: async function (response) {
                // Verify Payment
                try {
                    await api.post('/payment/razorpay/verify', {
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    })
                    router.push('/payment/success')
                } catch (e) {
                    alert("Payment Verification Failed")
                    router.push('/payment/failed')
                }
            },
            prefill: {
                name: "User Name", // Should fetch from auth store
                email: "user@example.com",
                contact: "9999999999"
            }, 
            theme: {
                color: "#4F46E5"
            }
        };
        const rzp1 = new window.Razorpay(options);
        rzp1.open();
        rzp1.on('payment.failed', function (response){
                alert(response.error.description);
                router.push('/payment/failed')
        });
    } catch (e) {
        console.error(e)
        alert("Something went wrong")
    } finally {
        processing.value = false
    }
}

const handleStripePayment = async () => {
    processing.value = true
    try {
        // Create Payment Intent
        const intentRes = await api.post('/payment/stripe/create-intent', {
            booking_id: booking.value.id
        })
        
        const { clientSecret } = intentRes.data

        const result = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: 'User Name', // Should fetch from auth store
                },
            },
        })

        if (result.error) {
            console.error(result.error)
            alert(result.error.message)
            router.push('/payment/failed')
        } else {
            if (result.paymentIntent.status === 'succeeded') {
                router.push('/payment/success')
            }
        }
    } catch (e) {
        console.error(e)
        alert("Stripe Payment Failed")
    } finally {
        processing.value = false
    }
}
</script>
