/**
 * Client-side validation for the product form.
 * Returns errors in the same { field: [msg] } shape as Laravel 422
 * so the component treats both sources identically.
 */
export function useProductValidation() {
  function validate(form) {
    const errors = {}

    // name
    const nameRo = form.name?.ro?.trim() ?? ''
    if (!nameRo)
      errors['name.ro'] = ['Название (RO) обязательно']
    else if (nameRo.length < 3)
      errors['name.ro'] = ['Название (RO) — минимум 3 символа']
    else if (nameRo.length > 200)
      errors['name.ro'] = ['Название (RO) — максимум 200 символов']

    const nameRu = form.name?.ru?.trim() ?? ''
    if (!nameRu)
      errors['name.ru'] = ['Название (RU) обязательно']

    // slug / code
    if (!form.slug?.trim())
      errors.slug = ['Slug обязателен']

    const code = form.code?.trim() ?? ''
    if (!code)
      errors.code = ['Код (SKU) обязателен']
    else if (code.length < 2)
      errors.code = ['Код (SKU) — минимум 2 символа']
    else if (code.length > 50)
      errors.code = ['Код (SKU) — максимум 50 символов']

    // price
    if (form.price === '' || form.price == null)
      errors.price = ['Цена обязательна']
    else if (parseFloat(form.price) < 0)
      errors.price = ['Цена не должна быть меньше 0']

    // relations
    if (!form.category_id || form.category_id === '0')
      errors.category_id = ['Категория обязательна']

    if (!form.brand_id || form.brand_id === '0')
      errors.brand_id = ['Бренд обязателен']

    // description
    if (!form.description?.ro?.trim())
      errors['description.ro'] = ['Описание (RO) обязательно']

    if (!form.description?.ru?.trim())
      errors['description.ru'] = ['Описание (RU) обязательно']

    // short_description
    const shortRo = form.short_description?.ro?.trim() ?? ''
    if (!shortRo)
      errors['short_description.ro'] = ['Краткое описание (RO) обязательно']
    else if (shortRo.length > 500)
      errors['short_description.ro'] = ['Краткое описание (RO) — максимум 500 символов']

    const shortRu = form.short_description?.ru?.trim() ?? ''
    if (!shortRu)
      errors['short_description.ru'] = ['Краткое описание (RU) обязательно']
    else if (shortRu.length > 500)
      errors['short_description.ru'] = ['Краткое описание (RU) — максимум 500 символов']

    // classifiers
    if (!form.outer_material_id || form.outer_material_id === '0')
      errors.outer_material_id = ['Внешний материал обязателен']

    if (!form.lining_material_id || form.lining_material_id === '0')
      errors.lining_material_id = ['Подкладка обязательна']

    if (!form.filling_id || form.filling_id === '0')
      errors.filling_id = ['Наполнитель обязателен']

    if (!form.season || form.season === 'none')
      errors.season = ['Сезон обязателен']

    if (!form.length || form.length === 'none')
      errors.length = ['Длина обязательна']

    // variants
    form.variants?.forEach((v, i) => {
      if (!v.color_hex)
        errors[`variants.${i}.color`] = ['Цвет варианта обязателен']
      if (!v.size)
        errors[`variants.${i}.size`] = ['Размер варианта обязателен']
    })

    return errors
  }

  /** Flatten { field: [msg, ...] } → string[] for toast */
  function flattenErrors(errors) {
    return Object.values(errors).flatMap(v => (Array.isArray(v) ? v : [v]))
  }

  /** First message for a field (supports multiple fallback keys) */
  function fieldMsg(errors, ...keys) {
    for (const k of keys) {
      if (errors[k]) return errors[k]
    }
    return null
  }

  return { validate, flattenErrors, fieldMsg }
}