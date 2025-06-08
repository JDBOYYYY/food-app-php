import { defineStore } from "pinia";
import { ref } from "vue";

interface ModalOptions {
  title: string;
  message: string;
  confirmText?: string;
  cancelText?: string;
  type?: "alert" | "confirm";
}

export const useModalStore = defineStore("modal", () => {
  const isOpen = ref(false);
  const options = ref<ModalOptions | null>(null);

  // This will hold the 'resolve' function of the promise we return
  let _resolve: (value: boolean) => void;

  function show(opts: ModalOptions) {
    options.value = {
      type: "alert",
      confirmText: "OK",
      ...opts,
    };
    isOpen.value = true;
  }

  function showAlert(opts: Omit<ModalOptions, "type">) {
    show({ ...opts, type: "alert" });
  }

  function showConfirm(opts: Omit<ModalOptions, "type">): Promise<boolean> {
    show({
      ...opts,
      type: "confirm",
      confirmText: opts.confirmText || "Confirm",
      cancelText: opts.cancelText || "Cancel",
    });
    return new Promise<boolean>((resolve) => {
      _resolve = resolve;
    });
  }

  function resolvePromise(value: boolean) {
    if (_resolve) {
      _resolve(value);
    }
    isOpen.value = false;
  }

  function closeModal() {
    isOpen.value = false;
  }

  return {
    isOpen,
    options,
    showAlert,
    showConfirm,
    resolvePromise,
    closeModal,
  };
});