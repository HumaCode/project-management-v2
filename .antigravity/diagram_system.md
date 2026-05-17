# Diagram System Module

## Overview
The Diagram System module introduces an interactive, professional diagram builder tailored for developers and system architects. The initial phase focused on a robust **Flowchart Builder** powered by Mermaid.js and Alpine.js, integrated directly into the premium glassmorphism dashboard.

## Key Features Implemented

### 1. Interactive Flowchart Builder
- **State Management**: Built on top of Alpine.js (`x-data="diagramBuilder"`) ensuring declarative, real-time synchronization between the builder form and the Mermaid canvas.
- **Dynamic Syntax Generation**: Automatic parsing of JSON state (nodes and links) into strict `mermaid` syntax with dynamic ID generation (A, B, C...).
- **Comprehensive Shapes**: Integrated 9 standard flowchart shapes (Terminal, Process, Decision, Database, Input/Output, Sub-process, Preparation, Connector) mapped directly to Mermaid syntax brackets (e.g., `[(Database)]`, `{Decision}`).
- **Custom Aesthetic Styling**: Injected `classDef` definitions to ensure all diagrams inherit the premium application theme (dark backgrounds, neon strokes, white text).

### 2. High-Resolution SVG to PNG Export
- **Canvas Conversion Pipeline**: Utilized `XMLSerializer` and standard HTML5 `<canvas>` to convert Mermaid-generated SVGs into downloadable, high-res PNG images.
- **Intelligent Edge Labeling**: Developed a custom `textShadow` halo effect to ensure line labels do not visually intersect with their respective paths, resolving default Mermaid clipping issues.
- **Bounding Box Optimization**: Synchronized `viewBox` and `bBox` to ensure exported images correctly map SVG dimensions to actual pixel densities without truncation.
- **Transparent Exports**: Ensured background defaults to transparent with fully readable high-contrast stroke outlines suitable for external documentation.

### 3. Asynchronous Data Persistence
- **Auto-Sync Mechanism**: Implemented `debounced` background saves via Axios, persisting diagram schema (`json_data`) and compiled SVG/Mermaid markup (`content`) seamlessly to the database without interrupting user flow.
- **Cross-Site Request Forgery (CSRF)**: Standardized dynamic token extraction from the `meta` tag for all Axios requests ensuring security.

### 4. UI/UX Refinements
- **Dark Mode Overrides**: Forcibly styled standard `<select>` and Select2 components to respect the `#1e293b` aesthetic, resolving native Windows/Chrome styling overrides.
- **Fluid Grid Loaders**: Implemented an absolute-positioned `spinner-border` overlay for the Index page to prevent layout breaking (`col-12` disruption) during asynchronous data fetching.
- **Real-time Feedback**: Standardized the use of `SCA.loading`, `SCA.dialog`, and `SCA.toast` for operations like deletions and saves to provide consistent user assurance.

## Next Steps (Roadmap)
1. **ERD Builder**: Expand the `diagramData` structure to support relational tables, primary keys, foreign keys, and cardinalities.
2. **DFD Builder**: Implement specific node types for Data Flow Diagrams (Entities, Processes, Data Stores).
3. **Advanced Layout Options**: Allow custom directional flows (Top-to-Bottom vs. Left-to-Right) via user toggles.
