"use client"

import { useState, useEffect, useRef } from "react"
import { PageLayout } from "@/components/page-layout"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Badge } from "@/components/ui/badge"
import { Checkbox } from "@/components/ui/checkbox"
import { ScrollArea } from "@/components/ui/scroll-area"
import { QrCode, Webhook, Loader2, RefreshCw, CheckCircle, XCircle, Clock, AlertTriangle, Users } from 'lucide-react'

interface Instance {
  name: string
  connectionStatus: string
  profileName?: string
  profilePictureUrl?: string
}

interface WebhookResult {
  instanceName: string
  success: boolean
  message: string
}

export default function EvolutionManagerPage() {
  // QR Code Tab State
  const [instanceName, setInstanceName] = useState("")
  const [qrCodeImage, setQrCodeImage] = useState("")
  const [qrLoading, setQrLoading] = useState(false)
  const [qrError, setQrError] = useState("")
  const [timer, setTimer] = useState(0)
  const [timerActive, setTimerActive] = useState(false)

  // Webhook Tab State
  const [instances, setInstances] = useState<Instance[]>([])
  const [selectedInstances, setSelectedInstances] = useState<string[]>([])
  const [webhookUrl, setWebhookUrl] = useState("")
  const [webhookLoading, setWebhookLoading] = useState(false)
  const [loadingInstances, setLoadingInstances] = useState(false)
  const [webhookResults, setWebhookResults] = useState<WebhookResult[]>([])
  const [webhookStatus, setWebhookStatus] = useState<"success" | "error" | "warning" | "">("")

  const timerRef = useRef<NodeJS.Timeout | null>(null)
  const currentInstanceRef = useRef("")

  // URLs dos webhooks n8n
  const WEBHOOK_CREATE_INSTANCE = "https://formulario.sedrux.site/webhook/criar-instancia-evolution"
  const WEBHOOK_UPDATE_QR = "https://formulario.sedrux.site/webhook/atualiza-qrcode"
  const WEBHOOK_UPDATE_WEBHOOK = "https://formulario.sedrux.site/webhook/atualiza-webhook"

  // URLs diretas da Evolution API (fallback)
  const EVOLUTION_API_URL = "https://evolution.sedrux.site"
  const EVOLUTION_API_KEY = "5f21094829fd8a7bde2d365c8dd07ecf"

  useEffect(() => {
    loadInstances()
    return () => {
      if (timerRef.current) {
        clearInterval(timerRef.current)
      }
    }
  }, [])

  const loadInstances = async () => {
    setLoadingInstances(true)
    try {
      const response = await fetch(`${EVOLUTION_API_URL}/instance/fetchInstances`, {
        method: "GET",
        headers: {
          apikey: EVOLUTION_API_KEY,
          "Content-Type": "application/json",
        },
      })

      if (!response.ok) {
        throw new Error("Failed to fetch instances")
      }

      const data: Instance[] = await response.json()
      setInstances(data)
    } catch (error) {
      console.error("Error loading instances:", error)
      setInstances([])
    } finally {
      setLoadingInstances(false)
    }
  }

  const handleInstanceToggle = (instanceName: string) => {
    setSelectedInstances((prev) =>
      prev.includes(instanceName) ? prev.filter((name) => name !== instanceName) : [...prev, instanceName],
    )
  }

  const handleSelectAll = () => {
    if (selectedInstances.length === instances.length) {
      setSelectedInstances([])
    } else {
      setSelectedInstances(instances.map((instance) => instance.name))
    }
  }

  const handleSelectByStatus = (status: string) => {
    const filteredInstances = instances.filter((instance) => instance.connectionStatus === status)
    setSelectedInstances(filteredInstances.map((instance) => instance.name))
  }

  const startTimer = () => {
    setTimer(30)
    setTimerActive(true)

    timerRef.current = setInterval(() => {
      setTimer((prev) => {
        if (prev <= 1) {
          updateQRCode()
          return 30
        }
        return prev - 1
      })
    }, 1000)
  }

  const stopTimer = () => {
    if (timerRef.current) {
      clearInterval(timerRef.current)
      timerRef.current = null
    }
    setTimerActive(false)
    setTimer(0)
  }

  const updateQRCode = async () => {
    if (!currentInstanceRef.current) {
      setQrError("Por favor, insira o nome da instância.")
      return
    }

    setQrLoading(true)
    setQrError("")
    setQrCodeImage("")

    try {
      const response = await fetch(WEBHOOK_UPDATE_QR, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ instanceName: currentInstanceRef.current }),
      })

      const contentType = response.headers.get("content-type")
      let imgSrc = ""

      if (contentType && contentType.includes("application/json")) {
        const data = await response.json()
        imgSrc = `data:image/png;base64,${data.qrCodeBase64}`
      } else {
        const blob = await response.blob()
        imgSrc = URL.createObjectURL(blob)
      }

      setQrCodeImage(imgSrc)
    } catch (error) {
      console.error("Erro ao atualizar QR code:", error)
      setQrError(
        error instanceof Error
          ? `Erro: ${error.message}`
          : "Erro ao atualizar QR code. Verifique se o nome da instância é válido e tente novamente.",
      )
    } finally {
      setQrLoading(false)
    }
  }

  const generateMockQRCode = (instanceName: string) => {
    const canvas = document.createElement("canvas")
    const ctx = canvas.getContext("2d")

    if (!ctx) return null

    canvas.width = 200
    canvas.height = 200

    ctx.fillStyle = "white"
    ctx.fillRect(0, 0, 200, 200)

    ctx.strokeStyle = "black"
    ctx.lineWidth = 2
    ctx.strokeRect(10, 10, 180, 180)

    ctx.fillStyle = "black"
    ctx.font = "12px Arial"
    ctx.textAlign = "center"
    ctx.fillText("QR Code Demo", 100, 80)
    ctx.fillText(instanceName, 100, 100)
    ctx.fillText("Conecte seu WhatsApp", 100, 120)

    for (let i = 0; i < 10; i++) {
      for (let j = 0; j < 10; j++) {
        if (Math.random() > 0.5) {
          ctx.fillRect(20 + i * 16, 130 + j * 6, 6, 6)
        }
      }
    }

    return canvas.toDataURL("image/png")
  }

  const generateQRCode = async () => {
    if (!instanceName.trim()) {
      setQrError("Por favor, insira o nome da instância.")
      return
    }

    setQrLoading(true)
    setQrError("")
    setQrCodeImage("")
    currentInstanceRef.current = instanceName

    try {
      try {
        const response = await fetch(WEBHOOK_CREATE_INSTANCE, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ instanceName: instanceName.trim() }),
        })

        if (response.ok) {
          const contentType = response.headers.get("content-type")
          let imgSrc = ""

          if (contentType && contentType.includes("application/json")) {
            const data = await response.json()
            imgSrc = `data:image/png;base64,${data.qrCodeBase64}`
          } else {
            const blob = await response.blob()
            imgSrc = URL.createObjectURL(blob)
          }

          setQrCodeImage(imgSrc)
          startTimer()
          return
        }
      } catch (webhookError) {
        console.warn("Webhook n8n falhou, gerando QR code demo:", webhookError)
      }

      const mockImage = generateMockQRCode(instanceName.trim())
      if (mockImage) {
        setQrCodeImage(mockImage)
        setQrError("⚠️ Usando QR Code de demonstração. Verifique a conectividade com a API Evolution.")
        startTimer()
      } else {
        throw new Error("Não foi possível gerar QR Code")
      }
    } catch (error) {
      console.error("Erro ao gerar QR code:", error)
      setQrError(
        error instanceof Error
          ? `Erro: ${error.message}`
          : "Erro ao gerar QR code. Verifique se o nome da instância é válido e tente novamente.",
      )
    } finally {
      setQrLoading(false)
    }
  }

  const updateWebhookForInstance = async (instanceName: string, webhookUrl: string): Promise<WebhookResult> => {
    try {
      // Primeiro, tenta usar o webhook n8n
      try {
        const response = await fetch(WEBHOOK_UPDATE_WEBHOOK, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            instanceName: instanceName,
            webhookUrl: webhookUrl,
          }),
        })

        const text = await response.text()

        if (text.trim() === "OK") {
          return {
            instanceName,
            success: true,
            message: "Webhook atualizado via n8n",
          }
        }
      } catch (webhookError) {
        console.warn(`Webhook n8n falhou para ${instanceName}, tentando API direta:`, webhookError)
      }

      // Se o webhook falhar, tenta a API direta
      const directResponse = await fetch(`${EVOLUTION_API_URL}/webhook/set/${instanceName}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          apikey: EVOLUTION_API_KEY,
        },
        body: JSON.stringify({
          webhook: {
            enabled: true,
            url: webhookUrl,
            byEvents: false,
            base64: true,
            events: ["MESSAGES_UPSERT"],
          },
        }),
      })

      if (directResponse.ok) {
        return {
          instanceName,
          success: true,
          message: "Webhook atualizado via API direta",
        }
      } else {
        return {
          instanceName,
          success: false,
          message: `Erro ${directResponse.status}: ${directResponse.statusText}`,
        }
      }
    } catch (error) {
      return {
        instanceName,
        success: false,
        message: error instanceof Error ? error.message : "Erro desconhecido",
      }
    }
  }

  const updateWebhooksBulk = async () => {
    if (selectedInstances.length === 0 || !webhookUrl.trim()) {
      setWebhookResults([
        {
          instanceName: "Erro",
          success: false,
          message: "Selecione pelo menos uma instância e informe a URL do webhook",
        },
      ])
      setWebhookStatus("error")
      return
    }

    setWebhookLoading(true)
    setWebhookResults([])
    setWebhookStatus("")

    const results: WebhookResult[] = []

    // Processa as instâncias em lotes para evitar sobrecarga
    const batchSize = 3
    for (let i = 0; i < selectedInstances.length; i += batchSize) {
      const batch = selectedInstances.slice(i, i + batchSize)

      const batchPromises = batch.map((instanceName) => updateWebhookForInstance(instanceName, webhookUrl.trim()))

      const batchResults = await Promise.all(batchPromises)
      results.push(...batchResults)

      // Atualiza os resultados em tempo real
      setWebhookResults([...results])

      // Pequena pausa entre lotes
      if (i + batchSize < selectedInstances.length) {
        await new Promise((resolve) => setTimeout(resolve, 500))
      }
    }

    // Determina o status geral
    const successCount = results.filter((r) => r.success).length
    const totalCount = results.length

    if (successCount === totalCount) {
      setWebhookStatus("success")
    } else if (successCount > 0) {
      setWebhookStatus("warning")
    } else {
      setWebhookStatus("error")
    }

    setWebhookLoading(false)
  }

  const getConnectionStatusBadge = (status: string) => {
    switch (status) {
      case "open":
        return <Badge className="bg-green-500 text-white">Conectado</Badge>
      case "close":
        return <Badge variant="destructive">Desconectado</Badge>
      case "connecting":
        return <Badge className="bg-yellow-500 text-white">Conectando</Badge>
      default:
        return <Badge variant="outline">{status}</Badge>
    }
  }

  const getStatusBadge = () => {
    switch (webhookStatus) {
      case "success":
        return (
          <Badge variant="secondary" className="bg-green-500">
            <CheckCircle className="w-3 h-3 mr-1" />
            Sucesso
          </Badge>
        )
      case "error":
        return (
          <Badge variant="destructive">
            <XCircle className="w-3 h-3 mr-1" />
            Erro
          </Badge>
        )
      case "warning":
        return (
          <Badge variant="secondary" className="bg-yellow-500">
            <AlertTriangle className="w-3 h-3 mr-1" />
            Parcial
          </Badge>
        )
      default:
        return null
    }
  }

  return (
    <PageLayout title="Gerenciar Evolution API" breadcrumbs={[{ label: "Evolution Manager" }]}>
      <div className="max-w-6xl mx-auto">
        <Card className="border-2 border-orange-500 shadow-lg shadow-orange-500/20">
          <CardHeader className="text-center">
            <CardTitle className="flex items-center justify-center gap-2 text-2xl text-orange-400">
              <QrCode className="w-6 h-6" />
              Agentes de IA com N8N - By Ric Neves
            </CardTitle>
            <CardDescription>Gerencie suas instâncias Evolution API</CardDescription>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="qrcode" className="w-full">
              <TabsList className="grid w-full grid-cols-2">
                <TabsTrigger value="qrcode" className="flex items-center gap-2">
                  <QrCode className="w-4 h-4" />
                  Gerar QR Code
                </TabsTrigger>
                <TabsTrigger value="webhook" className="flex items-center gap-2">
                  <Webhook className="w-4 h-4" />
                  Atualizar Webhook
                </TabsTrigger>
              </TabsList>

              {/* QR Code Tab */}
              <TabsContent value="qrcode" className="space-y-6">
                <div className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="instanceName">Nome da Instância Evolution API</Label>
                    <Input
                      id="instanceName"
                      value={instanceName}
                      onChange={(e) => setInstanceName(e.target.value)}
                      placeholder="Informe o nome da instância Evolution API"
                      className="border-orange-500 focus:border-orange-400"
                      disabled={qrLoading}
                    />
                  </div>

                  <Button
                    onClick={generateQRCode}
                    disabled={qrLoading || !instanceName.trim()}
                    className="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold"
                  >
                    {qrLoading ? (
                      <>
                        <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                        Gerando QR Code...
                      </>
                    ) : (
                      <>
                        <QrCode className="w-4 h-4 mr-2" />
                        Gerar QR Code
                      </>
                    )}
                  </Button>

                  {qrError && (
                    <Alert variant="destructive">
                      <XCircle className="h-4 w-4" />
                      <AlertDescription>{qrError}</AlertDescription>
                    </Alert>
                  )}

                  {qrCodeImage && (
                    <div className="space-y-4">
                      <Card className="p-4">
                        <div className="text-center">
                          <img
                            src={qrCodeImage || "/placeholder.svg"}
                            alt="QR Code"
                            className="mx-auto max-w-full h-auto border-2 border-orange-500 rounded-lg"
                            onError={() => {
                              setQrError("Erro ao carregar a imagem do QR Code")
                              setQrCodeImage("")
                            }}
                          />
                        </div>
                      </Card>

                      {timerActive && (
                        <div className="text-center">
                          <Badge variant="outline" className="text-yellow-400 border-yellow-400">
                            <Clock className="w-3 h-3 mr-1" />
                            Novo QR Code em: {timer}s
                          </Badge>
                        </div>
                      )}

                      <div className="flex gap-2">
                        <Button
                          onClick={updateQRCode}
                          variant="outline"
                          className="flex-1 bg-transparent"
                          disabled={qrLoading}
                        >
                          <RefreshCw className="w-4 h-4 mr-2" />
                          Atualizar QR Code
                        </Button>
                        <Button
                          onClick={stopTimer}
                          variant="outline"
                          className="flex-1 bg-transparent"
                          disabled={!timerActive}
                        >
                          Parar Timer
                        </Button>
                      </div>
                    </div>
                  )}
                </div>
              </TabsContent>

              {/* Webhook Tab */}
              <TabsContent value="webhook" className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2">
                  {/* Seleção de Instâncias */}
                  <Card>
                    <CardHeader>
                      <CardTitle className="flex items-center gap-2">
                        <Users className="w-5 h-5" />
                        Selecionar Instâncias
                      </CardTitle>
                      <CardDescription>Escolha as instâncias para atualizar o webhook</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                      <div className="flex flex-wrap gap-2">
                        <Button
                          variant="outline"
                          size="sm"
                          onClick={handleSelectAll}
                          disabled={loadingInstances || instances.length === 0}
                        >
                          {selectedInstances.length === instances.length ? "Desmarcar Todas" : "Selecionar Todas"}
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          onClick={() => handleSelectByStatus("open")}
                          disabled={loadingInstances}
                        >
                          Conectadas
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          onClick={() => handleSelectByStatus("close")}
                          disabled={loadingInstances}
                        >
                          Desconectadas
                        </Button>
                        <Button variant="outline" size="sm" onClick={loadInstances} disabled={loadingInstances}>
                          <RefreshCw className="w-4 h-4 mr-1" />
                          Atualizar
                        </Button>
                      </div>

                      {loadingInstances ? (
                        <div className="text-center py-4">
                          <Loader2 className="w-8 h-8 animate-spin mx-auto" />
                          <p className="text-sm text-muted-foreground mt-2">Carregando instâncias...</p>
                        </div>
                      ) : instances.length === 0 ? (
                        <Alert>
                          <AlertDescription>
                            Nenhuma instância encontrada. Verifique se há instâncias criadas na Evolution API.
                          </AlertDescription>
                        </Alert>
                      ) : (
                        <ScrollArea className="h-64 border rounded-lg p-3">
                          <div className="space-y-2">
                            {instances.map((instance) => (
                              <div
                                key={instance.name}
                                className="flex items-center justify-between p-2 hover:bg-muted/40 rounded"
                              >
                                <div className="flex items-center space-x-3">
                                  <Checkbox
                                    checked={selectedInstances.includes(instance.name)}
                                    onCheckedChange={() => handleInstanceToggle(instance.name)}
                                  />
                                  <div>
                                    <p className="text-sm font-medium">{instance.name}</p>
                                    {instance.profileName && (
                                      <p className="text-xs text-muted-foreground">{instance.profileName}</p>
                                    )}
                                  </div>
                                </div>
                                {getConnectionStatusBadge(instance.connectionStatus)}
                              </div>
                            ))}
                          </div>
                        </ScrollArea>
                      )}

                      {selectedInstances.length > 0 && (
                        <p className="text-sm text-muted-foreground">
                          {selectedInstances.length} instância(s) selecionada(s)
                        </p>
                      )}
                    </CardContent>
                  </Card>

                  {/* Configuração do Webhook */}
                  <Card>
                    <CardHeader>
                      <CardTitle>Configuração do Webhook</CardTitle>
                      <CardDescription>URL do webhook para receber mensagens</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                      <div className="space-y-2">
                        <Label htmlFor="webhookUrl">URL do Webhook</Label>
                        <Input
                          id="webhookUrl"
                          value={webhookUrl}
                          onChange={(e) => setWebhookUrl(e.target.value)}
                          placeholder="https://seu-webhook.com/endpoint"
                          className="border-orange-500 focus:border-orange-400"
                          disabled={webhookLoading}
                        />
                      </div>

                      <Button
                        onClick={updateWebhooksBulk}
                        disabled={webhookLoading || selectedInstances.length === 0 || !webhookUrl.trim()}
                        className="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold"
                      >
                        {webhookLoading ? (
                          <>
                            <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                            Atualizando Webhooks...
                          </>
                        ) : (
                          <>
                            <Webhook className="w-4 h-4 mr-2" />
                            Atualizar Webhooks ({selectedInstances.length})
                          </>
                        )}
                      </Button>

                      {webhookStatus && <div className="text-center">{getStatusBadge()}</div>}
                    </CardContent>
                  </Card>
                </div>

                {/* Resultados */}
                {webhookResults.length > 0 && (
                  <Card>
                    <CardHeader>
                      <CardTitle>Resultados da Atualização</CardTitle>
                      <CardDescription>Status da atualização para cada instância</CardDescription>
                    </CardHeader>
                    <CardContent>
                      <ScrollArea className="h-48">
                        <div className="space-y-2">
                          {webhookResults.map((result, index) => (
                            <div key={index} className="flex items-center justify-between p-2 border rounded-lg">
                              <div className="flex items-center gap-2">
                                {result.success ? (
                                  <CheckCircle className="w-4 h-4 text-green-500" />
                                ) : (
                                  <XCircle className="w-4 h-4 text-red-500" />
                                )}
                                <span className="font-medium">{result.instanceName}</span>
                              </div>
                              <span className="text-sm text-muted-foreground">{result.message}</span>
                            </div>
                          ))}
                        </div>
                      </ScrollArea>
                    </CardContent>
                  </Card>
                )}
              </TabsContent>
            </Tabs>

            {/* Connection Status Info */}
            <Card className="mt-6 bg-muted/20">
              <CardHeader className="pb-3">
                <CardTitle className="text-lg">Status da Conexão</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-2 text-sm">
                  <div className="flex justify-between">
                    <span>Evolution API:</span>
                    <Badge variant="outline" className="text-green-400 border-green-400">
                      {EVOLUTION_API_URL}
                    </Badge>
                  </div>
                  <div className="flex justify-between">
                    <span>Instâncias Carregadas:</span>
                    <Badge variant="outline" className="text-blue-400 border-blue-400">
                      {instances.length}
                    </Badge>
                  </div>
                  <div className="flex justify-between">
                    <span>Selecionadas:</span>
                    <Badge variant="outline" className="text-yellow-400 border-yellow-400">
                      {selectedInstances.length}
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </CardContent>
        </Card>
      </div>
    </PageLayout>
  )
}