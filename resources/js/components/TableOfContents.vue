<script setup lang="ts">
import { computed } from 'vue';
import { marked } from 'marked';

const props = defineProps<{
  content: string;
}>();

interface TocItem {
  level: number;
  text: string;
  id: string;
}

const tableOfContents = computed<TocItem[]>(() => {
  const tokens = marked.lexer(props.content);
  const headings: TocItem[] = [];

  tokens.forEach((token) => {
    if (token.type === 'heading') {
      headings.push({
        level: token.depth,
        text: token.text,
        id: token.text.toLowerCase().replace(/[^\w\s]/g, '').replace(/\s+/g, '-')
      });
    }
  });

  return headings;
});

const scrollToHeading = (id: string) => {
  const element = document.getElementById(id);
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<template>
  <ul v-if="tableOfContents.length > 0" class="space-y-2">
    <li
      v-for="item in tableOfContents"
      :key="item.id"
      :style="{
        paddingLeft: `${(item.level - 2) * 1}rem`
      }"
    >
      <a
        href="#"
        @click.prevent="scrollToHeading(item.id)"
        class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition"
      >
        {{ item.text }}
      </a>
    </li>
  </ul>
  <p v-else class="text-gray-500 dark:text-gray-400 text-sm italic">
    No headings found in this article
  </p>
</template>
