<template>
    <div>
        <!-- Loop through each tag in the 'tags' array and create a checkbox for it -->
        <label v-for="tag in tags" :key="tag">
            <input type="checkbox" v-model="selectedTags" :value="tag" /> {{ tag }}
        </label>
    </div>

    <button @click="fetchLinks">Fetch Links</button>

    <!-- Loop through each link in the 'links' array and create a list item for it -->
    <ul>
        <li v-for="link in links" :key="link.id">
            <a :href="link.url" target="_blank">{{ link.title }}</a> - {{ link.comments }}
        </li>
    </ul>
</template>

<script>
export default {
    data() {
        return {
            tags: ['laravel', 'vue', 'vue.js', 'php', 'api'],
            selectedTags: [],
            links: [], 
        };
    },
    methods: {
        // Method to fetch links based on selected tags
        async fetchLinks() {
            // If no tags are selected, exit the method
            if (this.selectedTags.length === 0) return;

            const response = await fetch(`/api/links?tags=${this.selectedTags.join(',')}`);
            console.log(response.data);

            this.links = await response.json();
            console.log(this.links);
        },

    }
}
</script>

<style scoped>
div {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

label {
    display: inline-flex;
    align-items: center;
    margin-right: 15px;
    font-size: 14px;
    color: #495057;
    cursor: pointer;
}

input[type="checkbox"] {
    margin-right: 6px;
    cursor: pointer;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    margin: 10px 5px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

button:active {
    background-color: #004085;
}

ul {
    list-style-type: none;
    padding-left: 0;
    margin-top: 20px;
}

li {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

li:hover {
    transform: translateY(-2px);
}

a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    margin-right: 5px;
}

a:hover {
    text-decoration: underline;
}

a:active {
    color: #0056b3;
}
</style>