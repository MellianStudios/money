<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-800 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.auth.user" href="/dashboard" class="text-sm text-gray-700 underline dark:text-white">
                Dashboard
            </inertia-link>
            <template v-else>
                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline dark:text-white">
                    Log in
                </inertia-link>
                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline dark:text-white">
                    Register
                </inertia-link>
            </template>
        </div>
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8 text-center">
            <span class="bg-clip-text text-transparent bg-gradient-to-l from-blue-400 to-purple-500 text-9xl">
                Currency pair stats
            </span>
            <a href="/currency-pairs" class="block dark:text-red-500 text-5xl mt-8">ENTER</a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    }
}
</script>
