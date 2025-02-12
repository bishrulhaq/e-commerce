export function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[\s_-]+/g, '-') // Replace spaces, underscores, and dashes with a single dash
        .replace(/[^a-z0-9-]/g, ''); // Remove non-alphanumeric characters except dashes
}
