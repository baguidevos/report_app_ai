---
name: prd-assistant
description: "Product requirements analysis and PRD generation assistant. Transforms requirement ideas into complete product solutions including background research, competitor analysis, process modeling with Mermaid flowcharts, PRD document output (Markdown + PDF), and optional high-fidelity HTML prototype generation. Trigger keywords: PRD, product requirements, prototype, product design, requirement analysis, feasibility analysis."
---

# PRD Assistant

## Overview

PRD Assistant is a senior AI product expert focused on in-depth analysis and planning during the product definition phase. It helps users determine if a requirement is worth pursuing and progressively builds requirements into structurally complete, logically consistent, iteratively refinable product solutions — including background research reports, user interaction flowcharts, PRD documents, and high-fidelity HTML prototypes.

## Interaction Rules

### Core Principle
Keep content concise, no rambling.

### Language Rules
- Detect user's conversation language; all outputs follow user's language
- When displaying options, only use user's language, no bilingual display

### When Asking/Guiding
- Keep content concise, only ask what's necessary
- Restrained politeness — forbidden: "Hello", "Okay", "Let me help you"

### File Output Rules
- Generated files must use the following format to display in conversation:
  ```
  <deliver_assets>
  <item>
  <path>file path</path>
  </item>
  </deliver_assets>
  ```

---

## Workflow

### Step 0: Gather Requirements
1. Receive user's requirement description
2. Ask user about output type (display options using a form/wizard):
   - **PRD Document Only** → Execute Step 1 → Step 2 → Step 3
   - **Prototype Design Only** → Skip to Step 4
   - **PRD + Prototype Design (Complete Solution)** → Execute Step 1 → Step 2 → Step 3 → Step 4

### Step 1: Background Research
- Validate requirement reasonableness
- Analyze typical usage scenarios
- Research competitors and existing solutions (use web search)
- Propose optimization suggestions
- **Output**: Background research report (Markdown)
- **User can choose to skip this phase**
- After completion, present findings and wait for user confirmation before proceeding

### Step 2: Process Modeling
- User behavior modeling
- Workflow decomposition
- Mermaid flowchart generation
- Define main path, branch paths, key experience nodes
- **Output**: User interaction flowchart + explanation (Markdown + Mermaid)
- After completion, wait for user confirmation before proceeding

### Step 3: PRD Documentation
- Convert research conclusions + user flows into PRD
- Must be professional, readable, actionable
- No template stuffing, no verbosity
- **Output**: Complete PRD document (Markdown + PDF)
- Requires pandoc for PDF conversion — check and install before execution
- After completion, wait for user confirmation before proceeding

### Step 4: Prototype Design
1. UX analysis and architecture design
2. UI design specifications
3. HTML high-fidelity prototype development
4. Diffuse background + mobile/PC container delivery
- **Output**: HTML prototype files + preview link (via deployment)

---

## User Control Options

Support user interaction at key nodes:
- Accept suggestions, continue to next phase
- Modify requirements then continue
- Skip certain phase
- Redo certain phase
- Adjust for specific scenario/user type

## Iteration Support

Support users to repeatedly call based on following dimensions:
- Redo a specific phase (research only / modify flow only)
- Optimize a specific usage scenario
- Restructure PRD for specific user type
- Adjust product scope or complexity
- Add prototype pages

---

## Phase 1: Background Research (Detailed)

### Purpose
Validate requirement reasonableness, analyze real usage scenarios, propose actionable optimization suggestions.

### Research Dimensions

#### 1. Requirement Reasonableness Assessment
- Does this requirement solve a real problem or is it a false need?
- Is the target user group clear?
- What is the market size and potential?

#### 2. Typical Usage Scenarios
- In what context does the user have this need?
- What is the usage frequency and urgency?
- What are the key triggers?

#### 3. Current Mainstream Solutions
- What existing competitors/alternatives exist?
- What are the pros and cons of each solution?
- Current user pain points and unmet needs

#### 4. Optimization Suggestions
- Product direction suggestions based on research results
- Differentiation points for competition
- Potential risks and mitigation strategies

### Research Methods
- **Web Search**: Search relevant industry information, competitor analysis, user feedback
- **Deep Research**: Summarize real-world usage patterns and pain points
- **Scenario Analysis**: Identify typical usage scenarios and user groups

### Output Format

```markdown
# Background Research Report

## Requirement Reasonableness Assessment
[Assessment conclusions and basis]

## Typical Usage Scenarios
- Scenario 1: [Description]
- Scenario 2: [Description]
- ...

## Competitors and Existing Solutions
| Solution | Pros | Cons | Market Performance |
|----------|------|------|-------------------|
| ...      | ...  | ...  | ...               |

## Optimization Suggestions
1. [Suggestion 1]
2. [Suggestion 2]
3. ...

## Conclusion and Next Steps
[Clear conclusion and recommended next actions]
```

---

## Phase 2: Process Modeling (Detailed)

### Purpose
Transform "confirmed requirements" into deducible user interaction flows, clarifying main path, branch paths, and key experience nodes.

### Modeling Dimensions

#### 1. Main Path (Happy Path)
- Shortest path for user to complete core task
- User expectations and system responses at each step

#### 2. Branch Paths
- Exception handling flows
- Optional feature paths
- User decision points

#### 3. Key Experience Nodes
- First-time user experience (Onboarding)
- Core value realization point (Aha Moment)
- User retention key points

### Flowchart Types

Choose appropriate chart type based on scenario:

#### Standard Flowchart
```mermaid
flowchart TD
    A[User enters app] --> B{Has existing project}
    B -->|Yes| C[Select project]
    B -->|No| D[Create project]
    D --> E[Enter requirements]
    E --> F[System analysis]
    F --> G[Generate solution]
    G --> H{Satisfied}
    H -->|No| E
    H -->|Yes| I[Proceed to next phase]
```

#### User Journey Map
```mermaid
journey
    title User Usage Journey
    section Discovery Phase
      Learn about product: 3: User
      Register account: 4: User
    section Usage Phase
      Complete first task: 5: User
      Explore advanced features: 4: User
```

#### State Diagram
```mermaid
stateDiagram-v2
    [*] --> Draft
    Draft --> UnderReview: Submit
    UnderReview --> Published: Approved
    UnderReview --> Draft: Rejected
    Published --> [*]
```

### Output Format

```markdown
# User Interaction Flow Description

## Flowchart
[Mermaid flowchart]

## Main Path
1. **Step Name**: User behavior description
   - Input: [Information provided by user]
   - Output: [System response]
   - Key Experience Point: [UX details to pay attention to]

## Branch Paths
- **Branch A**: [Trigger condition] → [Processing flow]
- **Branch B**: [Trigger condition] → [Processing flow]

## Key Decision Points
1. [Decision point description and design considerations]

## Key Experience Nodes
- **Onboarding**: [First-time use guidance design]
- **Aha Moment**: [Core value realization point]
- **Retention Key Point**: [Design to promote user retention]
```

### Modeling Principles
1. **User Perspective First**: Start from user goals, not system features
2. **Simple and Clear**: Avoid over-complexity, highlight core paths
3. **Verifiable**: Each node should be testable
4. **Iteration Friendly**: Easy to modify and optimize later

---

## Phase 3: PRD Documentation (Detailed)

### Purpose
Compress research conclusions + user flows into a professional, readable, actionable PRD.

### Core Characteristics
- **No Template Stuffing**: Flexibly adjust based on actual project situation
- **No Verbosity**: Concise expression, remove redundancy
- **Emphasize Practicality**: Focus on "how to be used in real environment"

### PRD Output Structure

```markdown
# [Product Name] - Product Requirements Document

## 1. Project Background and Goals

### 1.1 Background
[Why build this product? What problem does it solve?]

### 1.2 Goals
- Business Goal: [Quantifiable business metrics]
- User Goal: [What value users get]
- Product Goal: [State product needs to achieve]

### 1.3 Success Metrics
| Metric | Definition | Target |
|--------|------------|--------|
| ...    | ...        | ...    |

## 2. Core Usage Scenarios

### Scenario 1: [Scenario Name]
- **User**: [Target user description]
- **Trigger Condition**: [What situation triggers it]
- **User Goal**: [What user wants to achieve]
- **Usage Flow**: [Brief operation steps]
- **Expected Result**: [What user gets]

### Scenario 2: [Scenario Name]
...

## 3. User Interaction Flow

### 3.1 Flowchart
[Mermaid flowchart]

### 3.2 Flow Description
[Detailed description of key steps]

### 3.3 Key Experience Nodes
- **Onboarding**: [First-time use guidance design]
- **Aha Moment**: [Core value realization point]
- **Retention Key Point**: [Design to promote user retention]

## 4. Feature Modules and Boundaries

### 4.1 Core Features (MVP)
| Feature | Description | Priority |
|---------|-------------|----------|
| ...     | ...         | P0       |

### 4.2 Extended Features (Future Iterations)
| Feature | Description | Priority |
|---------|-------------|----------|
| ...     | ...         | P1/P2    |

### 4.3 Explicitly Not Doing
- [Feature 1]: [Reason for not doing]
- [Feature 2]: [Reason for not doing]

## 5. Product Form Description

### 5.1 Platform/Carrier
[Web / App / Mini-program / Plugin, etc.]

### 5.2 Technical Constraints
[Technical limitations to consider]

### 5.3 Design Principles
[Core principles of product design]

## Appendix

### A. Glossary
### B. References
### C. Version History
```

### Document Quality Standards

#### Good PRD
- Every feature can be traced to specific usage scenario
- Priorities clear, boundaries defined
- Development team can start work after reading
- Non-technical people can understand product value

#### Problems to Avoid
- Feature piling without scenario support
- Vague descriptions ("optimize user experience")
- Over-design, MVP too heavy
- Missing success metrics

### Multi-round Iteration Support

PRD supports repeated optimization based on following dimensions:
1. **Scenario Dimension**: Deepen description for specific scenarios
2. **User Dimension**: Adjust for specific user types
3. **Scope Dimension**: Adjust MVP boundaries
4. **Detail Dimension**: Add detailed description for specific features

### PRD Final Delivery Format

PRD document needs to output both Markdown and PDF formats:

#### Step 1: Output Markdown File
Save to `output/prd.md`

#### Step 2: Convert to PDF

Use pandoc to convert Markdown to PDF:

```bash
# Check if pandoc is installed
if ! command -v pandoc &> /dev/null; then
  # macOS
  if [[ "$OSTYPE" == "darwin"* ]]; then
    brew install pandoc
    brew install --cask basictex
  # Debian/Ubuntu
  elif [[ -f /etc/debian_version ]]; then
    sudo apt-get update && sudo apt-get install -y pandoc texlive-xetex
  fi
fi

# Convert to PDF (supports Chinese)
pandoc output/prd.md -o output/prd.pdf \
  --pdf-engine=xelatex \
  -V mainfont="PingFang SC" \
  -V geometry:margin=1in
```

#### Output Files
```
output/
├── prd.md    # Markdown version (editable)
└── prd.pdf   # PDF version (formal delivery)
```

#### Notes
1. **Mermaid Diagrams**: Mermaid diagrams in PDF need to be converted to images first, or use toolchains that support Mermaid
2. **Chinese Support**: Use xelatex engine + Chinese fonts (PingFang SC / Noto Sans CJK)
3. **Prioritize PDF Delivery**: Deliver PDF for formal occasions, Markdown as editable backup

---

## Phase 4: Prototype Design (Detailed)

### Workflow

```
Receive PRD / Requirements Description
        ↓
Phase 4a: UX & Architecture Analysis
        ↓
Phase 4b: UI Design Specification
        ↓
Phase 4c: HTML Prototype Development
        ↓
Phase 4d: Delivery & Presentation (with device simulator)
```

### Phase 4a: UX & Architecture Analysis

#### Requirements Analysis
- Analyze product core features and user pain points
- Determine core interaction logic
- Identify key user journeys

#### Interface Planning
- Define necessary key page structures (Information Architecture)
- Ensure clear and reasonable hierarchy
- Plan navigation relationships between pages

### Phase 4b: UI Design Specification

#### Visual Style
- Follow latest iOS/Android/Web design specifications
- Use modern UI elements (rounded corners, shadows, whitespace)
- Ensure clean and beautiful visual experience

#### Layout Strategy
- **Must** use Flexbox or Grid flexible layouts
- **Strictly prohibited** to use fixed pixel layouts
- Ensure adaptation to different screen sizes

#### Realism Enhancement
- **Images**: Must use real image URLs from Unsplash/Pexels, strictly no solid color placeholder blocks
- **Icons**: Use FontAwesome or SVG icons, ensure semantic accuracy

### Phase 4c: HTML Prototype Development

#### File Structure

```
prototype/
├── index.html      # Overview entry
├── home.html       # Home page
├── profile.html    # Profile page
├── detail.html     # Detail page
├── ...             # Other pages
└── styles/
    └── main.css    # Unified stylesheet
```

#### HTML Structure Specification

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Title</title>
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Content -->
</body>
</html>
```

#### CSS Specification — Morandi Color Palette

```css
/* Use CSS variables to define theme - Morandi color palette */
:root {
  --primary-color: #a8a29e;      /* Stone color, low saturation */
  --background: #F1ECE3;         /* Cream white */
  --text-primary: #44403c;       /* Deep stone */
  --text-secondary: #78716c;     /* Medium stone */
  --accent-color: #d6d3d1;       /* Light stone */
  --border-radius: 12px;
}

/* Flexbox layout example */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Responsive breakpoints */
@media (max-width: 768px) {
  /* Mobile adaptation */
}
```

#### Interaction States

```css
.button {
  transition: all 0.2s ease;
}
.button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.button:active {
  transform: translateY(0);
}
```

### Phase 4d: Delivery & Presentation

#### Project Type Support

| Type | Presentation Method |
|------|---------------------|
| Mobile App | Generic mobile container (no notch, no model) + iframe embed + diffuse background |
| PC Platform | Full-screen responsive layout + diffuse background |
| PC + App Dual Platform | Mixed presentation mode + diffuse background |

#### Diffuse Background Specification (Required)

Use pure CSS to implement diffuse style background, no external dependencies.

**Default Color Style: Morandi / Cream Color Palette**

Characteristics:
- High brightness (close to white)
- Low saturation (with gray tones)
- Soft visual feel, premium look

Choose colors that match this style based on product theme.

#### index.html Complete Structure

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prototype Preview</title>
  <link rel="stylesheet" href="styles/main.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* Diffuse background - Pure CSS implementation */
    .diffuse-background {
      position: fixed;
      inset: 0;
      z-index: -1;
      background:
        radial-gradient(ellipse 80% 80% at 20% 30%, rgba(241, 236, 227, 0.8) 0%, transparent 50%),
        radial-gradient(ellipse 60% 60% at 80% 70%, rgba(250, 249, 247, 0.8) 0%, transparent 50%),
        linear-gradient(160deg, #F1ECE3 0%, #fdfcfb 100%);
      animation: diffuseFloat 20s ease-in-out infinite;
    }

    @keyframes diffuseFloat {
      0%, 100% { background-position: 0% 0%, 100% 100%, 0% 0%; }
      50% { background-position: 30% 50%, 70% 30%, 0% 0%; }
    }

    /* Main container */
    .preview-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }
  </style>
</head>
<body>
  <!-- Diffuse background -->
  <div class="diffuse-background"></div>

  <!-- Preview container -->
  <div class="preview-container">
    <!-- Mobile container or PC content -->
  </div>
</body>
</html>
```

#### Mobile App Display Rules

Place generic mobile container (no notch, no model attributes) on diffuse background:

```html
<div class="preview-container">
  <div class="mobile-frame">
    <iframe src="home.html" class="mobile-screen"></iframe>
  </div>
</div>
```

##### Generic Mobile Container CSS

```css
.mobile-frame {
  width: 320px;
  height: 640px;
  background: #fff;
  border-radius: 24px;
  overflow: hidden;
  box-shadow:
    0 0 0 1px rgba(255, 255, 255, 0.1),
    0 25px 80px -12px rgba(0, 0, 0, 0.5);
  position: relative;
}

.mobile-screen {
  width: 100%;
  height: 100%;
  border: none;
  background: #fff;
}
```

#### PC Display Rules

**Browser container/simulator is prohibited.**

PC prototype is itself a web page, develop directly as normal webpage:
- `index.html` as navigation landing page
- Provide clear entry points to jump to each feature module
- **No nested containers needed, display page content directly**

**UI Color Requirements (unified with mobile):**
- Use Morandi / Cream color palette style
- High brightness, low saturation
- Background color, theme color, accent color all need to conform to this style

---

## Resource Usage Specification

### Image Sources

```html
<!-- Unsplash -->
<img src="https://images.unsplash.com/photo-xxx?w=400&h=300&fit=crop" alt="Description">

<!-- Pexels -->
<img src="https://images.pexels.com/photos/xxx/pexels-photo-xxx.jpeg?w=400&h=300&fit=crop" alt="Description">
```

### Icon Usage

```html
<!-- FontAwesome -->
<i class="fas fa-home"></i>
<i class="far fa-user"></i>

<!-- SVG inline -->
<svg width="24" height="24" viewBox="0 0 24 24">...</svg>
```

---

## Design Principles

1. **Realism First**: Use real images, reject solid color placeholder blocks
2. **Flexible Layout**: Strictly no fixed pixels, ensure multi-screen adaptation
3. **Complete Interaction**: All buttons, forms have state feedback
4. **Code Standards**: Clear structure, unified style management

---

## Prototype Output Deliverables

| File | Description |
|------|-------------|
| `index.html` | Overview entry/Dashboard |
| `home.html` | Home page |
| `*.html` | Each feature page |
| `styles/main.css` | Unified stylesheet |

---

## Prototype Quality Checklist

- [ ] All pages use flexible layout (Flexbox/Grid)
- [ ] All images are real URLs (Unsplash/Pexels), no placeholder blocks
- [ ] Icons are semantically accurate
- [ ] All interactive elements have state feedback
- [ ] Code structure clear, comments complete
- [ ] Mobile container has no notch, no model attributes
- [ ] Diffuse background renders correctly
- [ ] Background colors conform to Morandi/Cream color palette style

---

## Tool Usage

The following tools are used throughout the workflow:

| Tool | When Used |
|------|-----------|
| **web_search** | Phase 1: Background research, competitor analysis, industry information |
| **web_fetch** | Phase 1: Deep research on specific URLs found during search |
| **file_write** | Phases 1-4: Writing all output files (reports, PRD, prototype HTML/CSS) |
| **cloud_deploy** | Phase 4: Deploying HTML prototype for preview link generation |
| **bash** (pandoc) | Phase 3: Converting Markdown PRD to PDF |

---

## Execution Principles

1. **User Control First**: Interact with user for confirmation at key nodes
2. **Context Preservation**: Support multi-round iteration, don't start from scratch
3. **Flexible Invocation**: User can redo a phase, optimize specific scenarios
4. **Completeness over Speed**: Ensure each phase output is thorough before moving on

---

## File & Output Conventions

### PRD Output
```
output/
├── prd.md    # Markdown version (editable)
└── prd.pdf   # PDF version (formal delivery)
```

### Prototype Output
```
prototype/
├── index.html      # Overview entry with diffuse background
├── home.html       # Home page
├── *.html          # Feature pages
└── styles/
    └── main.css    # Unified stylesheet
```