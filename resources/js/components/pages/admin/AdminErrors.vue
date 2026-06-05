<template>
  <div>
    <!-- Toolbar -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
      <h2 class="text-lg font-semibold mr-auto">Ошибки ({{ meta.total ?? 0 }})</h2>

      <!-- Search -->
      <div class="relative">
        <Search class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-muted-foreground pointer-events-none" />
        <Input
          v-model="filters.search"
          placeholder="Поиск по ошибкам..."
          class="pl-8 w-56 h-8 text-sm"
          @input="debouncedLoad"
        />
      </div>

      <!-- Date from -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="outline" size="sm" class="h-8 gap-1.5 text-sm font-normal min-w-[130px] justify-start">
            <CalendarIcon class="h-3.5 w-3.5 text-muted-foreground" />
            <span :class="filters.date_from ? '' : 'text-muted-foreground'">
              {{ filters.date_from ? formatFilterDate(filters.date_from) : 'Дата от' }}
            </span>
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
          <Calendar
            :model-value="calendarFrom"
            @update:model-value="val => { filters.date_from = calendarToIso(val); debouncedLoad(); }"
            initial-focus
          />
          <div class="border-t px-3 py-2">
            <Button variant="ghost" size="sm" class="w-full text-xs" @click="filters.date_from = null; debouncedLoad()">
              Сбросить
            </Button>
          </div>
        </PopoverContent>
      </Popover>

      <!-- Date to -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="outline" size="sm" class="h-8 gap-1.5 text-sm font-normal min-w-[130px] justify-start">
            <CalendarIcon class="h-3.5 w-3.5 text-muted-foreground" />
            <span :class="filters.date_to ? '' : 'text-muted-foreground'">
              {{ filters.date_to ? formatFilterDate(filters.date_to) : 'Дата до' }}
            </span>
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
          <Calendar
            :model-value="calendarTo"
            @update:model-value="val => { filters.date_to = calendarToIso(val); debouncedLoad(); }"
            initial-focus
          />
          <div class="border-t px-3 py-2">
            <Button variant="ghost" size="sm" class="w-full text-xs" @click="filters.date_to = null; debouncedLoad()">
              Сбросить
            </Button>
          </div>
        </PopoverContent>
      </Popover>

      <!-- Clear all filters -->
      <Button
        v-if="filters.search || filters.date_from || filters.date_to"
        variant="ghost"
        size="sm"
        class="h-8 text-muted-foreground"
        @click="clearFilters"
      >
        <X class="h-3.5 w-3.5 mr-1" /> Сбросить
      </Button>
    </div>

    <!-- Table -->
    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead class="w-36">Время</TableHead>
            <TableHead class="w-32">Исключение</TableHead>
            <TableHead>Сообщение</TableHead>
            <TableHead class="w-48">URL</TableHead>
            <TableHead class="w-12" />
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-if="!logs.length">
            <TableCell colspan="5" class="text-center text-muted-foreground py-10">
              {{ loading ? 'Загрузка...' : 'Ошибок нет' }}
            </TableCell>
          </TableRow>
          <TableRow
            v-for="log in logs"
            :key="log.id"
            class="cursor-pointer hover:bg-muted/50"
            @click="openDetail(log)"
          >
            <TableCell class="text-xs text-muted-foreground whitespace-nowrap">
              {{ formatDate(log.created_at) }}
            </TableCell>
            <TableCell class="text-xs font-mono text-destructive">
              {{ shortException(log.properties?.exception ?? log.description) }}
            </TableCell>
            <TableCell class="text-xs max-w-xs truncate text-muted-foreground">
              {{ log.properties?.message }}
            </TableCell>
            <TableCell class="text-xs">
              <span class="font-semibold text-[10px] uppercase tracking-wide mr-1" :class="methodClass(log.properties?.method)">
                {{ log.properties?.method }}
              </span>
              <span class="font-mono text-muted-foreground">{{ shortUrl(log.properties?.url) }}</span>
            </TableCell>
            <TableCell @click.stop>
              <Button variant="ghost" size="icon" class="h-7 w-7 text-destructive" @click="remove(log.id)">
                <Trash2 class="h-3.5 w-3.5" />
              </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Pagination -->
    <div v-if="meta.last_page > 1" class="mt-4 flex items-center justify-between text-sm">
      <span class="text-muted-foreground">Страница {{ meta.current_page }} из {{ meta.last_page }}</span>
      <div class="flex gap-2">
        <Button variant="outline" size="sm" :disabled="meta.current_page <= 1" @click="load(meta.current_page - 1)">
          Назад
        </Button>
        <Button variant="outline" size="sm" :disabled="meta.current_page >= meta.last_page" @click="load(meta.current_page + 1)">
          Вперёд
        </Button>
      </div>
    </div>

    <!-- Detail Dialog -->
    <Dialog v-model:open="dialogOpen">
      <DialogContent class="max-w-3xl max-h-[85vh] flex flex-col">
        <DialogHeader>
          <DialogTitle class="text-destructive font-mono text-sm">
            {{ selectedLog?.properties?.exception ?? selectedLog?.description }}
          </DialogTitle>
          <DialogDescription class="flex flex-wrap gap-2 pt-1">
            <span class="text-xs text-muted-foreground">{{ selectedLog ? formatDate(selectedLog.created_at) : '' }}</span>
            <span
              v-if="selectedLog?.properties?.method"
              class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded"
              :class="methodClass(selectedLog.properties.method)"
            >{{ selectedLog.properties.method }}</span>
            <span class="text-xs font-mono text-muted-foreground">{{ selectedLog?.properties?.url }}</span>
          </DialogDescription>
        </DialogHeader>

        <div class="overflow-y-auto flex-1 space-y-4 pr-1">
          <!-- Message -->
          <div>
            <div class="text-[10px] uppercase tracking-widest font-medium text-muted-foreground mb-1.5">Сообщение об ошибке</div>
            <pre class="text-xs font-mono bg-muted rounded-md border px-3 py-2.5 whitespace-pre-wrap break-all leading-relaxed">{{ selectedLog?.properties?.message }}</pre>
          </div>

          <!-- File + line -->
          <div>
            <div class="text-[10px] uppercase tracking-widest font-medium text-muted-foreground mb-1.5">Файл</div>
            <p class="text-xs font-mono break-all">
              {{ selectedLog?.properties?.file }}<span class="text-destructive font-semibold">:{{ selectedLog?.properties?.line }}</span>
            </p>
          </div>

          <!-- Extra properties -->
          <div v-if="selectedExtraProps">
            <div class="text-[10px] uppercase tracking-widest font-medium text-muted-foreground mb-1.5">Дополнительные данные</div>
            <pre class="text-xs font-mono bg-muted rounded-md border px-3 py-2.5 whitespace-pre-wrap break-all leading-relaxed">{{ selectedExtraProps }}</pre>
          </div>
        </div>

        <DialogFooter class="gap-2">
          <Button variant="destructive" size="sm" @click="removeSelected">
            <Trash2 class="h-3.5 w-3.5 mr-1.5" /> Удалить
          </Button>
          <Button variant="outline" size="sm" @click="dialogOpen = false">Закрыть</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Trash2, Search, X, Calendar as CalendarIcon } from 'lucide-vue-next';
import { CalendarDate } from '@internationalized/date';
import { adminErrorService } from '@/services/adminErrorService.js';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import {
  Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle,
} from '@/components/ui/dialog';
import {
  Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';

const logs     = ref([]);
const meta     = ref({});
const loading  = ref(false);
const dialogOpen  = ref(false);
const selectedLog = ref(null);

const filters = ref({ search: '', date_from: null, date_to: null });

let debounceTimer = null;
function debouncedLoad() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => load(1), 400);
}

function clearFilters() {
  filters.value = { search: '', date_from: null, date_to: null };
  load(1);
}

async function load(page = 1) {
  loading.value = true;
  try {
    const params = { page, ...Object.fromEntries(Object.entries(filters.value).filter(([, v]) => v)) };
    const data = await adminErrorService.getAll(params);
    logs.value = data.data;
    meta.value = { current_page: data.current_page, last_page: data.last_page, total: data.total };
  } finally {
    loading.value = false;
  }
}

async function remove(id) {
  await adminErrorService.remove(id);
  logs.value = logs.value.filter(l => l.id !== id);
  meta.value.total = (meta.value.total ?? 1) - 1;
}

async function removeSelected() {
  await remove(selectedLog.value.id);
  dialogOpen.value = false;
}

function openDetail(log) {
  selectedLog.value = log;
  dialogOpen.value = true;
}

const selectedExtraProps = computed(() => {
  const props = selectedLog.value?.properties;
  if (!props) return null;
  const known = new Set(['exception', 'message', 'file', 'line', 'url', 'method']);
  const rest = Object.fromEntries(Object.entries(props).filter(([k]) => !known.has(k)));
  return Object.keys(rest).length ? JSON.stringify(rest, null, 2) : null;
});

// Calendar helpers — reka-ui Calendar works with CalendarDate objects
const calendarFrom = computed(() => isoToCalendar(filters.value.date_from));
const calendarTo   = computed(() => isoToCalendar(filters.value.date_to));

function isoToCalendar(iso) {
  if (!iso) return undefined;
  const [y, m, d] = iso.split('-').map(Number);
  return new CalendarDate(y, m, d);
}

function calendarToIso(val) {
  if (!val) return null;
  return `${val.year}-${String(val.month).padStart(2, '0')}-${String(val.day).padStart(2, '0')}`;
}

function formatFilterDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-');
  return `${d}.${m}.${y.slice(2)}`;
}

function formatDate(iso) {
  return new Date(iso).toLocaleString('ru-RU', {
    day: '2-digit', month: '2-digit', year: '2-digit',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
  });
}

function shortException(full) {
  if (!full) return '';
  return full.split('\\').pop();
}

function shortUrl(url) {
  if (!url) return '';
  try { return new URL(url).pathname; } catch { return url; }
}

function methodClass(method) {
  return {
    GET:    'text-blue-600',
    POST:   'text-green-600',
    PUT:    'text-amber-600',
    PATCH:  'text-amber-600',
    DELETE: 'text-red-600',
  }[method] ?? 'text-muted-foreground';
}

onMounted(() => load());
</script>
